
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalKuliahController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\RuanganController;

// Login hanya untuk pengguna yang belum login.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

// Logout hanya untuk pengguna yang sudah login.
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Halaman utama.
Route::get('/', function () {
    return redirect()->route('mahasiswa.index');
});

// Semua pengguna yang login boleh melihat daftar data.
Route::middleware('auth')->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class)
        ->only(['index']);

    Route::resource('dosen', DosenController::class)
        ->only(['index']);

    Route::resource('mata-kuliah', MataKuliahController::class)
        ->only(['index']);

    Route::resource('ruangan', RuanganController::class)
        ->only(['index']);

    Route::resource('kelas', KelasController::class)
        ->only(['index']);

    Route::resource('jadwal-kuliah', JadwalKuliahController::class)
        ->only(['index']);
});

// Hanya admin yang boleh melakukan CRUD.
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class)
        ->except(['index', 'show']);

    Route::resource('dosen', DosenController::class)
        ->except(['index', 'show']);

    Route::resource('mata-kuliah', MataKuliahController::class)
        ->except(['index', 'show']);

    Route::resource('ruangan', RuanganController::class)
        ->except(['index', 'show']);

    Route::resource('kelas', KelasController::class)
        ->except(['index', 'show'])
        ->parameters([
            'kelas' => 'kls',
        ]);

    Route::resource('jadwal-kuliah', JadwalKuliahController::class)
        ->except(['index', 'show']);
});
