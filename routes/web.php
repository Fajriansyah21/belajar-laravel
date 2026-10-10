<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalKuliahController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    
    Route::middleware('permission:menu-mahasiswa')->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class)
            ->only(['create', 'store'])->middleware('permission:tambah-data');
        Route::resource('mahasiswa', MahasiswaController::class)
            ->only(['edit', 'update'])->middleware('permission:ubah-data');
        Route::resource('mahasiswa', MahasiswaController::class)
            ->only(['destroy'])->middleware('permission:hapus-data');
        Route::resource('mahasiswa', MahasiswaController::class)
            ->only(['index', 'show']);
    });

    Route::middleware('permission:menu-dosen')->group(function () {
        Route::resource('dosen', DosenController::class)
            ->only(['create', 'store'])->middleware('permission:tambah-data');
        Route::resource('dosen', DosenController::class)
            ->only(['edit', 'update'])->middleware('permission:ubah-data');
        Route::resource('dosen', DosenController::class)
            ->only(['destroy'])->middleware('permission:hapus-data');
        Route::resource('dosen', DosenController::class)
            ->only(['index', 'show']);
    });

    Route::middleware('permission:menu-mata-kuliah')->group(function () {
        Route::resource('mata-kuliah', MataKuliahController::class)
            ->only(['create', 'store'])->middleware('permission:tambah-data');
        Route::resource('mata-kuliah', MataKuliahController::class)
            ->only(['edit', 'update'])->middleware('permission:ubah-data');
        Route::resource('mata-kuliah', MataKuliahController::class)
            ->only(['destroy'])->middleware('permission:hapus-data');
        Route::resource('mata-kuliah', MataKuliahController::class)
            ->only(['index', 'show']);
    });

    Route::middleware('permission:menu-ruangan')->group(function () {
        Route::resource('ruangan', RuanganController::class)
            ->only(['create', 'store'])->middleware('permission:tambah-data');
        Route::resource('ruangan', RuanganController::class)
            ->only(['edit', 'update'])->middleware('permission:ubah-data');
        Route::resource('ruangan', RuanganController::class)
            ->only(['destroy'])->middleware('permission:hapus-data');
        Route::resource('ruangan', RuanganController::class)
            ->only(['index', 'show']);
    });

    Route::middleware('permission:menu-kelas')->group(function () {
        Route::resource('kelas', KelasController::class)
            ->only(['create', 'store'])->middleware('permission:tambah-data')
            ->parameters(['kelas' => 'kls']);
        Route::resource('kelas', KelasController::class)
            ->only(['edit', 'update'])->middleware('permission:ubah-data')
            ->parameters(['kelas' => 'kls']);
        Route::resource('kelas', KelasController::class)
            ->only(['destroy'])->middleware('permission:hapus-data')
            ->parameters(['kelas' => 'kls']);
        Route::resource('kelas', KelasController::class)
            ->only(['index', 'show'])
            ->parameters(['kelas' => 'kls']);
    });

    Route::middleware('permission:menu-jadwal-kuliah')->group(function () {
        Route::resource('jadwal-kuliah', JadwalKuliahController::class)
            ->only(['create', 'store'])->middleware('permission:tambah-data');
        Route::resource('jadwal-kuliah', JadwalKuliahController::class)
            ->only(['edit', 'update'])->middleware('permission:ubah-data');
        Route::resource('jadwal-kuliah', JadwalKuliahController::class)
            ->only(['destroy'])->middleware('permission:hapus-data');
        Route::resource('jadwal-kuliah', JadwalKuliahController::class)
            ->only(['index', 'show']);
    });

    // Fitur RBAC & pengguna hanya untuk admin.
    Route::middleware('role:admin')->group(function () {
        Route::resource('role', RoleController::class)->except('show');
        Route::resource('pengguna', UserController::class)->except('show');
    });
});
