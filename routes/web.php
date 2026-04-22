<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\User\UserController as AdminUserContrtoller;
use App\Http\Controllers\Admin\Kategori\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\Buku\BukuController as AdminBukuController;
use App\Http\Controllers\Admin\Peminjaman\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\Pengembalian\PengembalianController as AdminPengembalianController;
use App\Http\Controllers\Siswa\Peminjaman\PeminjamanController as SiswaPeminjamanController;
use App\Http\Controllers\FileManager\FileManagerController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::prefix(env('ROUTE_PREFIX_LOGIN'))->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'login')->name('auth.login');
        Route::post('login', 'loginProcess')->name('auth.login.process');
        Route::get('register', 'register')->name('auth.register');
        Route::post('register', 'registerProcess')->name('auth.register.process');
        Route::post('logout', 'logout')->name('auth.logout');
    });
});

Route::get('/login', function () {
    return redirect()->route('auth.login');
})->name('login');

Route::prefix('soralib')->middleware('auth')->group( function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

    Route::controller(FileManagerController::class)->prefix('file-manager')->group(function () {
        Route::get('/', 'listImages')->name('filemanager.list');
        Route::post('upload', 'uploadImage')->name('filemanager.upload');
        Route::delete('{id}', 'deleteImage')->name('filemanager.delete');
    });

    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group( function () {
        Route::controller(AdminUserContrtoller::class)->prefix('administrator')->name('administrator.')->group(function () {
            Route::get('/', 'listAdmin')->name('list');
            Route::get('/create', 'createAdmin')->name('create');
            Route::post('/', 'storeAdmin')->name('store');
            Route::get('/{user}/edit', 'editAdmin')->name('edit');
            Route::put('/{user}', 'updateAdmin')->name('update');
            Route::delete('/{user}', 'destroyAdmin')->name('destroy');
        });

        Route::controller(AdminUserContrtoller::class)->prefix('anggota')->name('anggota.')->group( function () {
            Route::get('/', 'listSiswa')->name('list');
            Route::get('/{user}/edit', 'editSiswa')->name('edit');
            Route::put('/{user}', 'updateSiswa')->name('update');
            Route::get('/{user}', 'showSiswa')->name('detail');
            Route::delete('/{user}', 'destroySiswa')->name('destroy');
        });

        Route::controller(AdminKategoriController::class)->prefix('kategori-buku')->name('kategori.')->group( function () {
            Route::get('/', 'listCategories')->name('list');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{kategori}/edit', 'edit')->name('edit');
            Route::put('{kategori}', 'update')->name('update');
            Route::delete('{kategori}', 'destroy')->name('destroy');
        });

        Route::controller(AdminBukuController::class)->prefix('kelola-buku')->name('buku.')->group( function () {
            Route::get('/', 'listBuku')->name('list');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{buku}/edit', 'edit')->name('edit');
            Route::put('{buku}', 'update')->name('update');
            Route::delete('{buku}', 'destroy')->name('destroy');
        });

        Route::controller(AdminPeminjamanController::class)->prefix('peminjaman')->name('peminjaman.')->group( function () {
            Route::get('/', 'listPeminjaman')->name('list');
            Route::get('{peminjaman}', 'show')->name('show');
            Route::patch('{peminjaman}/status', 'updateStatus')->name('update-status');
            Route::delete('{peminjaman}', 'destroy')->name('destroy');
        });

        Route::controller(AdminPengembalianController::class)->prefix('pengembalian')->name('pengembalian.')->group( function () {
            Route::get('/', 'listPengembalian')->name('list');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{pengembalian}', 'show')->name('show');
            Route::get('{pengembalian}/edit', 'edit')->name('edit');
            Route::put('{pengembalian}', 'update')->name('update');
            Route::get('/{pengembalian}/send-whatsapp', 'sendWhatsApp')->name('send-whatsapp');
        });
    });

    Route::prefix('siswa')->middleware('role:siswa')->name('siswa.')->group( function () {
        Route::controller(SiswaPeminjamanController::class)->prefix('pinjam-buku')->name('peminjaman.')->group( function () {
            Route::get('/', 'listBuku')->name('list');
            Route::get('{buku}/pinjam', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });
    });
});

