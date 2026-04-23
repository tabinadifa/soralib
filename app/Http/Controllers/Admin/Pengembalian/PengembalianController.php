<?php

namespace App\Http\Controllers\Admin\Pengembalian;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PengembalianController extends Controller
{
    /* =======================
     * LIST PENGEMBALIAN
     * ======================= */
    public function listPengembalian(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $query = Pengembalian::with([
            'peminjaman:id,buku_id,peminjam_id,tanggal_pinjam,tanggal_kembali',
            'peminjaman.buku:id,judul_buku,penulis',
            'peminjaman.peminjam:id,name,username,email,phone',
            'fileBuktiPembayaran:id,file_name,file_path',
        ])->select(
            'id',
            'peminjaman_id',
            'tanggal_pengembalian',
            'kondisi_buku',
            'status',
            'denda',
            'metode_pembayaran',
            'file_bukti_pembayaran_id',
            'catatan',
            'created_at'
        );

        // Search
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->whereHas('peminjaman.peminjam', function ($sub) use ($keyword) {
                $sub->where('name', 'like', "%{$keyword}%")
                    ->orWhere('username', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            })->orWhereHas('peminjaman.buku', function ($sub) use ($keyword) {
                $sub->where('judul_buku', 'like', "%{$keyword}%")
                    ->orWhere('penulis', 'like', "%{$keyword}%");
            });
        }

        // Filter status pembayaran
        if ($request->filled('status')) {
            $status = $request->status;
            if (in_array($status, ['lunas', 'belum_lunas'], true)) {
                $query->where('status', $status);
            }
        }

        // Pagination
        $perPage = (int) $request->get('per_page', 10);
        $allowedSizes = [5, 10, 25, 50];
        if (!in_array($perPage, $allowedSizes, true)) {
            $perPage = 10;
        }

        $pengembalians = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pengembalian.list', [
            'pengembalians' => $pengembalians,
        ]);
    }

    /* =======================
     * FORM CREATE
     * ======================= */
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $peminjamanId = $request->query('peminjaman_id');

        // Jika ada parameter peminjaman_id, langsung ambil data peminjaman tersebut
        if ($peminjamanId) {
            $peminjaman = Peminjaman::with([
                'buku:id,judul_buku,penulis',
                'peminjam:id,name,username,kelas,nisn,phone'
            ])->find($peminjamanId);

            if (!$peminjaman) {
                return redirect()->route('admin.pengembalian.create')
                    ->with('error', 'Data peminjaman tidak ditemukan.');
            }

            // Pastikan status peminjaman adalah 'approve' dan belum dikembalikan
            if ($peminjaman->status !== 'approve') {
                return redirect()->route('admin.pengembalian.create')
                    ->with('error', 'Peminjaman ini tidak dapat dikembalikan (status bukan approve).');
            }

            // Cek apakah sudah ada pengembalian untuk peminjaman ini
            $existingReturn = Pengembalian::where('peminjaman_id', $peminjamanId)->exists();
            if ($existingReturn) {
                return redirect()->route('admin.pengembalian.create')
                    ->with('error', 'Peminjaman ini sudah memiliki catatan pengembalian.');
            }

            return view('admin.pengembalian.create', [
                'peminjaman' => $peminjaman, // data spesifik
                'files' => $this->getBuktiPembayaranFiles(),
                'peminjamans' => null, // tidak perlu daftar semua
            ]);
        }

        // Jika tidak ada parameter, tampilkan daftar peminjaman (seperti semula)
        $search = $request->get('search');

        $peminjamans = Peminjaman::with([
            'buku:id,judul_buku,penulis',
            'peminjam:id,name,username',
        ])->select(
            'id',
            'buku_id',
            'peminjam_id',
            'tanggal_pinjam',
            'tanggal_kembali',
            'total_buku',
            'status'
        )->where('status', 'approve')
            ->when($search, function ($query, $search) {
                $query->whereHas('peminjam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })->orWhereHas('buku', function ($q) use ($search) {
                    $q->where('judul_buku', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal_pinjam')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.pengembalian.create', [
            'peminjamans' => $peminjamans,
            'files' => $this->getBuktiPembayaranFiles(),
            'peminjaman' => null,
        ]);
    }

    /* =======================
     * STORE
     * ======================= */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'peminjaman_id' => ['required', 'exists:peminjaman,id'],
            'tanggal_pengembalian' => ['required', 'date'],
            'kondisi_buku' => ['required', 'in:baik,rusak_ringan,rusak_berat,hilang'],
            'denda_kondisi' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:lunas,belum_lunas'],
            'metode_pembayaran' => ['nullable', 'string', 'max:50'],
            'file_bukti_pembayaran_id' => ['nullable', 'exists:file_managers,id'],
            'catatan' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $peminjaman = Peminjaman::select('id', 'buku_id', 'total_buku', 'status', 'tanggal_kembali')
                ->whereKey($validated['peminjaman_id'])
                ->lockForUpdate()
                ->first();

            if (!$peminjaman) {
                throw ValidationException::withMessages([
                    'peminjaman_id' => 'Data peminjaman tidak ditemukan.',
                ]);
            }

            if ($peminjaman->status === 'returned') {
                throw ValidationException::withMessages([
                    'peminjaman_id' => 'Peminjaman ini sudah dikembalikan.',
                ]);
            }

            if ($peminjaman->status !== 'approve') {
                throw ValidationException::withMessages([
                    'peminjaman_id' => 'Hanya peminjaman berstatus approve yang dapat dikembalikan.',
                ]);
            }

            // Hitung total denda (telat + kondisi)
            $dendaTelat = $this->hitungDendaTelat(
                $validated['tanggal_pengembalian'],
                $peminjaman->tanggal_kembali
            );
            $dendaKondisi = (float) ($validated['denda_kondisi'] ?? 0);
            $totalDenda = $dendaTelat + $dendaKondisi;
            $statusPembayaran = $totalDenda > 0 ? $validated['status'] : 'lunas';

            Pengembalian::create([
                'peminjaman_id' => $validated['peminjaman_id'],
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
                'kondisi_buku' => $validated['kondisi_buku'],
                'status' => $statusPembayaran,
                'denda' => $totalDenda,
                'metode_pembayaran' => $validated['metode_pembayaran'] ?? null,
                'file_bukti_pembayaran_id' => $validated['file_bukti_pembayaran_id'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Kembalikan stok buku kecuali jika kondisi buku hilang.
            $buku = Buku::find($peminjaman->buku_id);
            if ($buku && $validated['kondisi_buku'] !== 'hilang') {
                $buku->increment('jumlah_stok', $peminjaman->total_buku);
            }

            $peminjaman->update([
                'status' => 'returned',
            ]);
        });

        return redirect()
            ->route('admin.pengembalian.list')
            ->with('success', 'Data pengembalian berhasil ditambahkan.');
    }

    /* =======================
     * SHOW
     * ======================= */
    public function show(Pengembalian $pengembalian)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pengembalian->load(
            'peminjaman.buku:id,judul_buku,penulis,penerbit,tahun_terbit,isbn',
            'peminjaman.peminjam:id,name,username,email,phone,address,kelas,nisn',
            'fileBuktiPembayaran'
        );

        return view('admin.pengembalian.show', [
            'pengembalian' => $pengembalian,
        ]);
    }

    /* =======================
     * FORM EDIT
     * ======================= */
    public function edit(Pengembalian $pengembalian)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pengembalian->loadMissing(
            'peminjaman.buku:id,judul_buku,penulis',
            'peminjaman.peminjam:id,name,username'
        );

        $peminjamans = Peminjaman::with([
            'buku:id,judul_buku,penulis',
            'peminjam:id,name,username',
        ])->select(
            'id',
            'buku_id',
            'peminjam_id',
            'tanggal_pinjam',
            'tanggal_kembali',
            'total_buku',
            'status'
        )->orderByDesc('tanggal_pinjam')
            ->get();

        return view('admin.pengembalian.edit', [
            'pengembalian' => $pengembalian,
            'peminjamans' => $peminjamans,
            'files' => $this->getBuktiPembayaranFiles(),
        ]);
    }

    /* =======================
     * UPDATE
     * ======================= */
    public function update(Request $request, Pengembalian $pengembalian)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'peminjaman_id' => ['required', 'exists:peminjaman,id'],
            'tanggal_pengembalian' => ['required', 'date'],
            'kondisi_buku' => ['required', 'in:baik,rusak_ringan,rusak_berat,hilang'],
            'denda_kondisi' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:lunas,belum_lunas'],
            'metode_pembayaran' => ['nullable', 'string', 'max:50'],
            'file_bukti_pembayaran_id' => ['nullable', 'exists:file_managers,id'],
            'catatan' => ['nullable', 'string'],
        ]);

        // Kunci relasi peminjaman saat edit untuk mencegah salah ubah data.
        if ((int) $validated['peminjaman_id'] !== (int) $pengembalian->peminjaman_id) {
            throw ValidationException::withMessages([
                'peminjaman_id' => 'Peminjaman tidak dapat diubah pada proses edit pengembalian.',
            ]);
        }

        $peminjaman = Peminjaman::select('id', 'tanggal_kembali')
            ->find($pengembalian->peminjaman_id);

        if (!$peminjaman) {
            throw ValidationException::withMessages([
                'peminjaman_id' => 'Data peminjaman tidak ditemukan.',
            ]);
        }

        // Hitung ulang denda telat
        $dendaTelat = $this->hitungDendaTelat(
            $validated['tanggal_pengembalian'],
            $peminjaman->tanggal_kembali
        );
        $dendaKondisi = (float) ($validated['denda_kondisi'] ?? 0);
        $totalDenda = $dendaTelat + $dendaKondisi;
        $statusPembayaran = $totalDenda > 0 ? $validated['status'] : 'lunas';

        $pengembalian->update([
            'peminjaman_id' => $pengembalian->peminjaman_id,
            'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
            'kondisi_buku' => $validated['kondisi_buku'],
            'status' => $statusPembayaran,
            'denda' => $totalDenda,
            'metode_pembayaran' => $validated['metode_pembayaran'] ?? null,
            'file_bukti_pembayaran_id' => $validated['file_bukti_pembayaran_id'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()
            ->route('admin.pengembalian.list')
            ->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    /* =======================
     * DELETE
     * ======================= */
    public function destroy(Pengembalian $pengembalian)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pengembalian->delete();

        return redirect()
            ->route('admin.pengembalian.list')
            ->with('success', 'Data pengembalian berhasil dihapus.');
    }

    /* =======================
     * KIRIM WHATSAPP
     * ======================= */
    public function sendWhatsApp(Pengembalian $pengembalian)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $peminjam = $pengembalian->peminjaman->peminjam;

        if (empty($peminjam->phone)) {
            return redirect()->back()
                ->with('error', 'Nomor WhatsApp peminjam belum diisi.');
        }

        // Bersihkan nomor HP
        $phone = preg_replace('/[^0-9]/', '', $peminjam->phone);
        if (!Str::startsWith($phone, '62') && !Str::startsWith($phone, '0')) {
            $phone = '62' . $phone;
        }
        if (Str::startsWith($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $message = $this->getWhatsAppMessageTemplate($pengembalian);
        $encodedMessage = rawurlencode($message);
        $whatsappUrl = "https://wa.me/{$phone}?text={$encodedMessage}";

        return redirect()->away($whatsappUrl);
    }

    /* =======================
     * HITUNG DENDA TELAT
     * ======================= */
    private function hitungDendaTelat(string $tanggalPengembalian, ?string $tanggalKembali): float
    {
        if (!$tanggalKembali) {
            return 0.0;
        }

        $tglKembali = Carbon::parse($tanggalKembali)->startOfDay();
        $tglPengembalian = Carbon::parse($tanggalPengembalian)->startOfDay();

        if ($tglPengembalian->lte($tglKembali)) {
            return 0.0;
        }

        $hariTelat = $tglKembali->diffInDays($tglPengembalian);
        return $hariTelat * 1000; // Denda per hari
    }

    /* =======================
     * TEMPLATE PESAN WHATSAPP
     * ======================= */
    private function getWhatsAppMessageTemplate(Pengembalian $pengembalian): string
    {
        $peminjam = $pengembalian->peminjaman->peminjam;
        $buku = $pengembalian->peminjaman->buku;
        $tanggalPengembalian = Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y');
        $kondisiMap = [
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            'hilang' => 'Hilang',
        ];
        $kondisi = $kondisiMap[$pengembalian->kondisi_buku] ?? ucfirst($pengembalian->kondisi_buku);
        $denda = $pengembalian->denda > 0 ? 'Rp ' . number_format($pengembalian->denda, 0, ',', '.') : 'Tidak ada denda';
        $statusPembayaran = ucfirst($pengembalian->status ?? 'Belum diproses');

        return "📢 *Informasi Pengembalian Buku*
        
Halo *{$peminjam->name}*,

Berikut adalah rincian pengembalian buku Anda:
• *Judul Buku*: {$buku->judul_buku}
• *Tanggal Pengembalian*: {$tanggalPengembalian}
• *Kondisi Buku*: {$kondisi}
• *Denda*: {$denda}
• *Status Pembayaran*: {$statusPembayaran}

Terima kasih telah menggunakan layanan perpustakaan.

- *Admin*";
    }

    private function getBuktiPembayaranFiles()
    {
        $folder = storage_path('app/public/uploads/bukti-pembayaran');

        if (File::exists($folder)) {
            foreach (File::files($folder) as $storedFile) {
                $relativePath = 'storage/uploads/bukti-pembayaran/' . $storedFile->getFilename();

                FileManager::firstOrCreate(
                    ['file_path' => $relativePath],
                    [
                        'file_name' => $storedFile->getFilename(),
                        'mime_type' => File::mimeType($storedFile->getPathname()) ?: 'application/octet-stream',
                        'size' => $storedFile->getSize(),
                        'uploaded_by' => Auth::id(),
                    ]
                );
            }
        }

        return FileManager::select('id', 'file_name', 'file_path', 'created_at')
            ->where('file_path', 'like', '%/uploads/bukti-pembayaran/%')
            ->orderByDesc('created_at')
            ->get();
    }
}
