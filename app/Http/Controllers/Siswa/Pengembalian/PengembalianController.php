<?php

namespace App\Http\Controllers\Siswa\Pengembalian;

use App\Http\Controllers\Controller;
use App\Models\FileManager;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PengembalianController extends Controller
{
	public function index(Request $request)
	{
		if (!Auth::check()) {
			return redirect()->route('auth.login')
				->with('error', 'Silakan login terlebih dahulu.');
		}

		$query = Pengembalian::with([
			'peminjaman:id,buku_id,peminjam_id,tanggal_pinjam,tanggal_kembali,total_buku,status',
			'peminjaman.buku:id,judul_buku,penulis',
		])->whereHas('peminjaman', function ($subQuery) {
			$subQuery->where('peminjam_id', Auth::id());
		})->select(
			'id',
			'peminjaman_id',
			'tanggal_pengembalian',
			'kondisi_buku',
			'status',
			'denda',
			'metode_pembayaran',
			'file_bukti_pembayaran_id',
			'created_at'
		);

		if ($request->filled('status')) {
			$query->where('status', $request->status);
		}

		$perPage = (int) $request->get('per_page', 10);
		$allowedSizes = [5, 10, 25, 50];
		if (!in_array($perPage, $allowedSizes, true)) {
			$perPage = 10;
		}

		$pengembalians = $query
			->latest()
			->paginate($perPage)
			->withQueryString();

		return view('siswa.riwayat-pengembalian.list', [
			'pengembalians' => $pengembalians,
		]);
	}

	public function show(Pengembalian $pengembalian)
	{
		if (!Auth::check()) {
			return redirect()->route('auth.login')
				->with('error', 'Silakan login terlebih dahulu.');
		}

		$pengembalian->load([
			'peminjaman:id,buku_id,peminjam_id,tanggal_pinjam,tanggal_kembali,total_buku,status',
			'peminjaman.buku:id,judul_buku,penulis,penerbit,tahun_terbit,isbn',
			'fileBuktiPembayaran:id,file_name,file_path',
		]);

		abort_unless((int) $pengembalian->peminjaman?->peminjam_id === (int) Auth::id(), 403);

		return view('siswa.riwayat-pengembalian.detail', [
			'pengembalian' => $pengembalian,
		]);
	}

	public function updatePembayaran(Request $request, Pengembalian $pengembalian)
	{
		if (!Auth::check()) {
			return redirect()->route('auth.login')
				->with('error', 'Silakan login terlebih dahulu.');
		}

		$pengembalian->loadMissing('peminjaman:id,peminjam_id');
		abort_unless((int) $pengembalian->peminjaman?->peminjam_id === (int) Auth::id(), 403);

		$validated = $request->validate([
			'metode_pembayaran' => ['required', 'in:QRIS,tunai,tidak_denda'],
			'bukti_pembayaran' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
		]);

		if ((int) $pengembalian->denda > 0) {
			if (($validated['metode_pembayaran'] ?? null) === 'tidak_denda') {
				return back()->withErrors([
					'metode_pembayaran' => 'Metode pembayaran tidak dapat menggunakan "tidak denda" karena ada nominal denda.',
				])->withInput();
			}
		} else {
			$validated['metode_pembayaran'] = 'tidak_denda';
		}

		$oldFileId = $pengembalian->file_bukti_pembayaran_id;
		if ($request->hasFile('bukti_pembayaran')) {
			$user = Auth::user();
			if (!$user instanceof User) {
				return redirect()->route('auth.login')
					->with('error', 'User tidak valid.');
			}

			$uploaded = $this->uploadBuktiPembayaran($request, $user);
			$validated['file_bukti_pembayaran_id'] = $uploaded->id;
		}

		$pengembalian->update([
			'metode_pembayaran' => $validated['metode_pembayaran'],
			'file_bukti_pembayaran_id' => $validated['file_bukti_pembayaran_id'] ?? $pengembalian->file_bukti_pembayaran_id,
		]);

		if (!empty($validated['file_bukti_pembayaran_id']) && $oldFileId && (int) $oldFileId !== (int) $validated['file_bukti_pembayaran_id']) {
			$this->deleteFileIfUnused((int) $oldFileId);
		}

		return redirect()
			->route('siswa.pengembalian.show', $pengembalian)
			->with('success', 'Data pembayaran pengembalian berhasil diperbarui.');
	}

	private function uploadBuktiPembayaran(Request $request, User $user): FileManager
	{
		$file = $request->file('bukti_pembayaran');
		$folder = 'bukti-pembayaran';

		$basePath = storage_path("app/public/uploads/{$folder}");
		if (!File::exists($basePath)) {
			File::makeDirectory($basePath, 0777, true);
		}

		$originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$extension = $file->getClientOriginalExtension();
		$cleanName = Str::slug($originalName);
		$fileName = time() . '_' . $cleanName . '.' . $extension;

		$file->storeAs("uploads/{$folder}", $fileName, 'public');

		return FileManager::create([
			'file_name' => $fileName,
			'file_path' => "storage/uploads/{$folder}/{$fileName}",
			'mime_type' => $file->getClientMimeType(),
			'size' => $file->getSize(),
			'uploaded_by' => $user->id,
		]);
	}

	private function deleteFileIfUnused(int $fileId): void
	{
		$isStillUsed = Pengembalian::where('file_bukti_pembayaran_id', $fileId)->exists();
		if ($isStillUsed) {
			return;
		}

		$file = FileManager::find($fileId);
		if (!$file) {
			return;
		}

		$relativePath = ltrim(str_replace('storage/', '', $file->file_path), '/');
		$fullPath = storage_path('app/public/' . $relativePath);
		if (File::exists($fullPath)) {
			File::delete($fullPath);
		}

		$file->delete();
	}
}