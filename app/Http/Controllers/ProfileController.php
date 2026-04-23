<?php

namespace App\Http\Controllers;

use App\Models\FileManager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
	public function index()
	{
		if (!Auth::check()) {
			return redirect()->route('auth.login')
				->with('error', 'Silakan login terlebih dahulu.');
		}

		return view('profile', [
			'user' => Auth::user(),
		]);
	}

	public function update(Request $request)
	{
		if (!Auth::check()) {
			return redirect()->route('auth.login')
				->with('error', 'Silakan login terlebih dahulu.');
		}

		$user = Auth::user();
		if (!$user instanceof User) {
			return redirect()->route('auth.login')
				->with('error', 'User tidak valid.');
		}

		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'username' => [
				'required',
				'string',
				'max:50',
				Rule::unique('users', 'username')->ignore($user->id),
			],
			'email' => [
				'required',
				'email',
				'max:255',
				Rule::unique('users', 'email')->ignore($user->id),
			],
			'nisn' => ['nullable', 'string', 'max:20'],
			'kelas' => ['nullable', 'string', 'max:50'],
			'phone' => ['nullable', 'string', 'max:13'],
			'address' => ['nullable', 'string', 'max:1000'],
			'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
		]);

		$oldProfileId = $user->profile_id;

		if ($request->hasFile('profile_photo')) {
			$uploadedPhoto = $this->uploadProfilePhoto($request, $user);
			$validated['profile_id'] = $uploadedPhoto->id;
		}

		$user->fill($validated);
		$user->save();

		if (!empty($validated['profile_id']) && $oldProfileId && (int) $oldProfileId !== (int) $validated['profile_id']) {
			$this->deleteFileManagerIfUnused((int) $oldProfileId);
		}

		return redirect()->route('profile')
			->with('success', 'Profil berhasil diperbarui.');
	}

	public function updatePassword(Request $request)
	{
		if (!Auth::check()) {
			return redirect()->route('auth.login')
				->with('error', 'Silakan login terlebih dahulu.');
		}

		$validated = $request->validate([
			'current_password' => ['required', 'string'],
			'new_password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
		], [
			'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
			'new_password.min' => 'Password baru minimal 8 karakter.',
			'new_password.different' => 'Password baru harus berbeda dari password saat ini.',
		]);

		$user = Auth::user();
		if (!$user instanceof User) {
			return redirect()->route('auth.login')
				->with('error', 'User tidak valid.');
		}
		if (!Hash::check($validated['current_password'], $user->password)) {
			throw ValidationException::withMessages([
				'current_password' => 'Password saat ini tidak sesuai.',
			]);
		}

		$user->password = Hash::make($validated['new_password']);
		$user->save();

		return redirect()->route('profile')
			->with('success', 'Password berhasil diperbarui.');
	}

	private function uploadProfilePhoto(Request $request, User $user): FileManager
	{
		$file = $request->file('profile_photo');
		$folder = 'profile';

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

	private function deleteFileManagerIfUnused(int $fileManagerId): void
	{
		$isStillUsed = User::where('profile_id', $fileManagerId)->exists();
		if ($isStillUsed) {
			return;
		}

		$file = FileManager::find($fileManagerId);
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
