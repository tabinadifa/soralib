<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PeminjamanController extends Controller
{
    /**
     * Daftar status yang diperbolehkan untuk filter.
     */
    private array $allowedStatuses = ['rejected', 'pending', 'approve'];

    /**
     * Menampilkan daftar peminjaman buku (dengan filter status, search, pagination).
     */
    public function listPeminjaman(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $query = Peminjaman::with([
            'buku:id,judul_buku,penulis',
            'peminjam:id,name,username,email',
        ])->select(
            'id',
            'buku_id',
            'peminjam_id',
            'total_buku',
            'tanggal_pinjam',
            'tanggal_kembali',
            'status',
            'created_at'
        );

        // Filter berdasarkan status
        if ($request->filled('status') && in_array($request->status, $this->allowedStatuses, true)) {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan nama peminjam / judul buku
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($builder) use ($keyword) {
                $builder->whereHas('peminjam', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', "%{$keyword}%")
                        ->orWhere('username', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                })->orWhereHas('buku', function ($sub) use ($keyword) {
                    $sub->where('judul_buku', 'like', "%{$keyword}%")
                        ->orWhere('penulis', 'like', "%{$keyword}%");
                });
            });
        }

        // Pagination
        $perPage = (int) $request->get('per_page', 10);
        $allowedSizes = [5, 10, 25, 50];
        if (!in_array($perPage, $allowedSizes, true)) {
            $perPage = 10;
        }

        $peminjaman = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.peminjaman.list', [
            'peminjaman' => $peminjaman,
            'allowedStatuses' => $this->allowedStatuses,
        ]);
    }

    /**
     * Menampilkan detail peminjaman buku.
     */
    public function show(Peminjaman $peminjaman)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $peminjaman->loadMissing([
            'buku:id,judul_buku,penulis,penerbit,tahun_terbit,isbn',
            'peminjam:id,name,username,email,phone,address,kelas,nisn'
        ]);

        return view('admin.peminjaman.show', [
            'peminjaman' => $peminjaman,
            'allowedStatuses' => $this->allowedStatuses,
        ]);
    }

    /**
     * Mengubah status peminjaman (approve/rejected) dan menyesuaikan stok buku.
     */
    public function updateStatus(Request $request, Peminjaman $peminjaman)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cegah perubahan jika status sudah returned (jika ada)
        if ($peminjaman->status === 'returned') {
            return back()->with('info', 'Peminjaman sudah dikembalikan, status tidak dapat diubah.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in($this->allowedStatuses)],
            'alasan_ditolak' => [
                Rule::requiredIf(fn() => $request->input('status') === 'rejected'),
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        // Jika status baru sama dengan status saat ini
        if ($peminjaman->status === $validated['status']) {
            return back()->with('info', 'Status peminjaman sudah sesuai.');
        }

        // Jika akan mengubah ke approve, cek ketersediaan stok buku
        if ($validated['status'] === 'approve' && $peminjaman->status !== 'approve') {
            $buku = Buku::find($peminjaman->buku_id);
            if (!$buku || $buku->jumlah_stok < $peminjaman->total_buku) {
                return back()->with('error', "Stok buku '{$buku->judul_buku}' tidak mencukupi. Tersedia: {$buku->jumlah_stok}, Diminta: {$peminjaman->total_buku}.");
            }
        }

        $previousStatus = $peminjaman->status;

        try {
            DB::transaction(function () use ($peminjaman, $validated, $previousStatus) {
                // Jika sebelumnya approve dan sekarang bukan approve -> kembalikan stok
                if ($previousStatus === 'approve' && $validated['status'] !== 'approve') {
                    $buku = Buku::find($peminjaman->buku_id);
                    if ($buku) {
                        $buku->increment('jumlah_stok', $peminjaman->total_buku);
                    }
                }

                // Update status dan alasan ditolak
                $peminjaman->update([
                    'status' => $validated['status'],
                    'alasan_ditolak' => $validated['status'] === 'rejected' ? ($validated['alasan_ditolak'] ?? null) : null,
                ]);

                // Jika menjadi approve dan sebelumnya bukan approve -> kurangi stok
                if ($validated['status'] === 'approve' && $previousStatus !== 'approve') {
                    $buku = Buku::find($peminjaman->buku_id);
                    if ($buku) {
                        $buku->decrement('jumlah_stok', $peminjaman->total_buku);
                    }
                }
            });

            return back()->with('success', 'Status peminjaman berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
