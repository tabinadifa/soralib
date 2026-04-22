<?php

namespace App\Http\Controllers\Admin\Buku;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\FileManager;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku (dengan search & pagination)
     */
    public function listBuku(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $query = Buku::with('kategori', 'gambar')
            ->select('id', 'kategori_id', 'judul_buku', 'deskripsi', 'jumlah_stok', 
                     'penulis', 'penerbit', 'tahun_terbit', 'bahasa', 'isbn', 
                     'gambar_buku_id', 'created_at');

        // Search: judul, penulis, isbn
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

        $bukus = $query->latest()->paginate($perPage)->withQueryString();

        $kategoriBukus = KategoriBuku::orderBy('nama_kategori')->get();

        return view('admin.buku.list', compact('bukus', 'kategoriBukus'));
    }

    /**
     * Menampilkan form tambah buku
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $kategoriBukus = KategoriBuku::orderBy('nama_kategori')->get();

        return view('admin.buku.create', [
            'kategoriBukus' => $kategoriBukus,
            'files' => FileManager::select('id', 'file_name', 'file_path', 'created_at')
                ->where('file_path', 'like', '%/uploads/gambar-buku/%')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    /**
     * Menyimpan data buku baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kategori_id'    => ['required', 'exists:kategori_buku,id'],
                'judul_buku'     => ['required', 'string', 'max:255'],
                'deskripsi'      => ['nullable', 'string'],
                'jumlah_stok'    => ['required', 'integer', 'min:0'],
                'penulis'        => ['nullable', 'string', 'max:255'],
                'penerbit'       => ['nullable', 'string', 'max:255'],
                'tahun_terbit'   => ['nullable', 'string', 'max:10'],
                'bahasa'         => ['nullable', 'string', 'max:50'],
                'isbn'           => ['nullable', 'string', 'max:20', 'unique:buku,isbn'],
                'gambar_buku_id' => ['nullable', 'exists:file_managers,id'],
            ]);

            // Format judul buku: huruf pertama setiap kata kapital
            $validated['judul_buku'] = ucwords(strtolower($validated['judul_buku']));

            Buku::create($validated);

            return redirect()
                ->route('admin.buku.list')
                ->with('success', 'Buku berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan buku: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit buku
     */
    public function edit(Buku $buku)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $kategoriBukus = KategoriBuku::orderBy('nama_kategori')->get();

        return view('admin.buku.edit', [
            'buku'          => $buku,
            'kategoriBukus' => $kategoriBukus,
            'files'         => FileManager::select('id', 'file_name', 'file_path', 'created_at')
                ->where('file_path', 'like', '%/uploads/gambar-buku/%')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    /**
     * Memperbarui data buku
     */
    public function update(Request $request, Buku $buku)
    {
        try {
            $validated = $request->validate([
                'kategori_id'    => ['required', 'exists:kategori_buku,id'],
                'judul_buku'     => ['required', 'string', 'max:255'],
                'deskripsi'      => ['nullable', 'string'],
                'jumlah_stok'    => ['required', 'integer', 'min:0'],
                'penulis'        => ['nullable', 'string', 'max:255'],
                'penerbit'       => ['nullable', 'string', 'max:255'],
                'tahun_terbit'   => ['nullable', 'string', 'max:10'],
                'bahasa'         => ['nullable', 'string', 'max:50'],
                'isbn'           => ['nullable', 'string', 'max:20', Rule::unique('buku', 'isbn')->ignore($buku->id)],
                'gambar_buku_id' => ['nullable', 'exists:file_managers,id'],
            ]);

            $validated['judul_buku'] = ucwords(strtolower($validated['judul_buku']));

            $buku->update($validated);

            return redirect()
                ->route('admin.buku.list')
                ->with('success', 'Buku berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui buku.');
        }
    }

    /**
     * Menghapus data buku
     */
    public function destroy(Buku $buku)
    {
        try {
            $buku->delete();

            return redirect()
                ->route('admin.buku.list')
                ->with('success', 'Buku berhasil dihapus.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan saat menghapus buku.');
        }
    }
}