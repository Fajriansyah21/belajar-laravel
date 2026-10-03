<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalKuliahController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\RuanganController;

// Route login (hanya tamu)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

// Route logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Halaman utama
Route::get('/', function () {
    return redirect()->route('mahasiswa.index');
});

// Semua user yang sudah login: hanya bisa melihat data
Route::middleware('auth')->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class)
        ->only(['index', 'show']);

    Route::resource('dosen', DosenController::class)
        ->only(['index', 'show']);

    Route::resource('mata-kuliah', MataKuliahController::class)
        ->only(['index', 'show']);

    Route::resource('ruangan', RuanganController::class)
        ->only(['index', 'show']);

    Route::resource('kelas', KelasController::class)
        ->only(['index', 'show'])
        ->parameters([
            'kelas' => 'kls',
        ]);

    Route::resource('jadwal-kuliah', JadwalKuliahController::class)
        ->only(['index', 'show']);
});

// Khusus admin: tambah, simpan, edit, perbarui, dan hapus data
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