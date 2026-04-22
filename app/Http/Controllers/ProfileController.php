<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
			'phone' => ['nullable', 'string', 'max:13'],
			'address' => ['nullable', 'string', 'max:1000'],
		]);

		$user->fill($validated);
		$user->save();

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
}
