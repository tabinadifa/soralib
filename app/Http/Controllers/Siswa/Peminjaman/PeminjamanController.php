<?php

namespace App\Http\Controllers\Siswa\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar buku yang tersedia untuk dipinjam (stok > 0)
     */
    public function listBuku(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Tampilkan buku yang memiliki stok > 0
        $query = Buku::with('kategori', 'gambar')
            ->select('id', 'kategori_id', 'judul_buku', 'deskripsi', 'jumlah_stok', 'penulis', 'penerbit', 'tahun_terbit', 'bahasa', 'isbn', 'gambar_buku_id', 'created_at')
            ->where('jumlah_stok', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_buku', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 10);
        $allowedSizes = [5, 10, 25, 50];
        if (!in_array($perPage, $allowedSizes, true)) {
            $perPage = 10;
        }

        $bukus = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $kategoriBukus = KategoriBuku::orderBy('nama_kategori')->get();

        return view('siswa.pinjam-buku.list', compact('bukus', 'kategoriBukus'));
    }

    /**
     * Menampilkan form peminjaman untuk buku tertentu
     */
    public function create(Request $request, Buku $buku)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek ketersediaan stok
        if ($buku->jumlah_stok <= 0) {
            return redirect()->route('siswa.peminjaman.list')
                ->with('error', 'Buku ini tidak tersedia untuk dipinjam (stok habis).');
        }

        $buku = Buku::with('gambar', 'kategori')
            ->select('id', 'kategori_id', 'judul_buku', 'jumlah_stok', 'deskripsi', 'penulis', 'penerbit', 'tahun_terbit', 'bahasa', 'isbn', 'gambar_buku_id')
            ->where('id', $buku->id)
            ->first();

        return view('siswa.pinjam-buku.create', compact('buku'));
    }

    /**
     * Menyimpan pengajuan peminjaman buku
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'buku_id'         => ['required', 'exists:buku,id'],
            'total_buku'      => ['required', 'integer', 'min:1'],
            'tanggal_pinjam'  => ['required', 'date', 'after_or_equal:today'],
            'tanggal_kembali' => ['required', 'date', 'after:tanggal_pinjam'],
        ]);

        $buku = Buku::find($validated['buku_id']);

        // Cek stok cukup
        if ($buku->jumlah_stok < $validated['total_buku']) {
            return back()->withInput()
                ->withErrors(['total_buku' => "Jumlah buku yang dipinjam tidak boleh melebihi stok tersedia ({$buku->jumlah_stok})."]);
        }

        // Simpan peminjaman dengan status pending
        Peminjaman::create([
            'buku_id'         => $validated['buku_id'],
            'peminjam_id'     => Auth::id(),
            'total_buku'      => $validated['total_buku'],
            'tanggal_pinjam'  => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'status'          => 'pending',
        ]);

        // (Opsional) Kurangi stok sementara? Biasanya stok baru dikurangi setelah disetujui.
        // Untuk sementara tidak dikurangi, karena masih pending.
        // Jika ingin mengurangi stok sementara, bisa dilakukan di sini, tapi lebih baik di proses approve oleh admin.

        return redirect()->route('siswa.peminjaman.list')
            ->with('success', 'Peminjaman berhasil diajukan. Silakan tunggu persetujuan dari petugas.');
    }

    /**
     * (Opsional) Menampilkan riwayat peminjaman milik peminjam yang sedang login
     */
    // public function riwayat(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('auth.login')
    //             ->with('error', 'Silakan login terlebih dahulu.');
    //     }

    //     $query = Peminjaman::with('buku')
    //         ->where('peminjam_id', Auth::id())
    //         ->select('id', 'buku_id', 'total_buku', 'tanggal_pinjam', 'tanggal_kembali', 'status', 'alasan_ditolak', 'created_at');

    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     $perPage = (int) $request->get('per_page', 10);
    //     $allowedSizes = [5, 10, 25, 50];
    //     if (!in_array($perPage, $allowedSizes, true)) {
    //         $perPage = 10;
    //     }

    //     $peminjaman = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

    //     return view('siswa.pinjam-buku.riwayat', compact('peminjaman'));
    // }
}