<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * ====================
     * MANAJEMEN ADMIN (CRUD penuh)
     * ====================
     */
    public function listAdmin(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $query = User::where('role', 'admin')->select(
            'id', 'name', 'username', 'email', 'role', 'created_at', 'last_active_at'
        );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $admins = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // Admin menampilkan active_since dan last_active
        $admins->getCollection()->transform(function ($admin) {
            $admin->active_since = $admin->created_at ? $admin->created_at->translatedFormat('M Y') : '-';
            $admin->last_active = $admin->last_active_at ? Carbon::parse($admin->last_active_at)->diffForHumans() : '-';
            return $admin;
        });

        return view('admin.administrator.list', compact('admins'));
    }

    public function createAdmin()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        return view('admin.administrator.create');
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'admin';

        User::create($validated);

        return redirect()->route('admin.administrator.list')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function editAdmin(User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->route('admin.administrator.list')->with('error', 'User bukan admin.');
        }
        return view('admin.administrator.edit', compact('user'));
    }

    public function updateAdmin(Request $request, User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->route('admin.administrator.list')->with('error', 'User bukan admin.');
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.administrator.list')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroyAdmin(User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->route('admin.administrator.list')->with('error', 'User bukan admin.');
        }
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.administrator.list')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.administrator.list')->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * ====================
     * MANAJEMEN SISWA (hanya edit, detail, hapus - tanpa create)
     * ====================
     */
    public function listSiswa(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $query = User::where('role', 'siswa')->select(
            'id', 'name', 'username', 'email', 'nisn', 'kelas', 'phone', 'address',
            'role', 'created_at', 'last_active_at'
        );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $siswas = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // Siswa hanya menampilkan last_active (tanpa active_since)
        $siswas->getCollection()->transform(function ($siswa) {
            $siswa->last_active = $siswa->last_active_at ? Carbon::parse($siswa->last_active_at)->diffForHumans() : '-';
            return $siswa;
        });

        return view('admin.anggota.list', compact('siswas'));
    }

    public function editSiswa(User $user)
    {
        if ($user->role !== 'siswa') {
            return redirect()->route('admin.anggota.list')->with('error', 'User bukan siswa.');
        }
        return view('admin.anggota.edit', compact('user'));
    }

    public function updateSiswa(Request $request, User $user)
    {
        if ($user->role !== 'siswa') {
            return redirect()->route('admin.anggota.list')->with('error', 'User bukan siswa.');
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nisn'     => ['nullable', 'string', 'max:50', Rule::unique('users', 'nisn')->ignore($user->id)],
            'kelas'    => ['nullable', 'string', 'max:50'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'address'  => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.anggota.list')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function showSiswa(User $user)
    {
        if ($user->role !== 'siswa') {
            return redirect()->route('admin.anggota.list')->with('error', 'User bukan siswa.');
        }
        return view('admin.anggota.show', compact('user'));
    }

    public function destroySiswa(User $user)
    {
        if ($user->role !== 'siswa') {
            return redirect()->route('admin.anggota.list')->with('error', 'User bukan siswa.');
        }
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.anggota.list')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.anggota.list')->with('success', 'Siswa berhasil dihapus.');
    }
}