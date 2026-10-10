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

    // CRUD Mahasiswa
    Route::middleware('permission:menu-mahasiswa')->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class)
            ->only(['index', 'show'])
            ->middleware('permission:mahasiswa.view');

        Route::resource('mahasiswa', MahasiswaController::class)
            ->only(['create', 'store'])
            ->middleware('permission:mahasiswa.create');

        Route::resource('mahasiswa', MahasiswaController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:mahasiswa.edit');

        Route::resource('mahasiswa', MahasiswaController::class)
            ->only(['destroy'])
            ->middleware('permission:mahasiswa.delete');
    });

    // CRUD Dosen
    Route::middleware('permission:menu-dosen')->group(function () {
        Route::resource('dosen', DosenController::class)
            ->only(['index', 'show'])
            ->middleware('permission:dosen.view');

        Route::resource('dosen', DosenController::class)
            ->only(['create', 'store'])
            ->middleware('permission:dosen.create');

        Route::resource('dosen', DosenController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:dosen.edit');

        Route::resource('dosen', DosenController::class)
            ->only(['destroy'])
            ->middleware('permission:dosen.delete');
    });

    // CRUD Mata Kuliah
    Route::middleware('permission:menu-mata-kuliah')->group(function () {
        Route::resource('mata-kuliah', MataKuliahController::class)
            ->only(['index', 'show'])
            ->middleware('permission:mata-kuliah.view');

        Route::resource('mata-kuliah', MataKuliahController::class)
            ->only(['create', 'store'])
            ->middleware('permission:mata-kuliah.create');

        Route::resource('mata-kuliah', MataKuliahController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:mata-kuliah.edit');

        Route::resource('mata-kuliah', MataKuliahController::class)
            ->only(['destroy'])
            ->middleware('permission:mata-kuliah.delete');
    });

    // CRUD Ruangan
    Route::middleware('permission:menu-ruangan')->group(function () {
        Route::resource('ruangan', RuanganController::class)
            ->only(['index', 'show'])
            ->middleware('permission:ruangan.view');

        Route::resource('ruangan', RuanganController::class)
            ->only(['create', 'store'])
            ->middleware('permission:ruangan.create');

        Route::resource('ruangan', RuanganController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:ruangan.edit');

        Route::resource('ruangan', RuanganController::class)
            ->only(['destroy'])
            ->middleware('permission:ruangan.delete');
    });

    // CRUD Kelas
    Route::middleware('permission:menu-kelas')->group(function () {
        Route::resource('kelas', KelasController::class)
            ->only(['index', 'show'])
            ->middleware('permission:kelas.view')
            ->parameters(['kelas' => 'kls']);

        Route::resource('kelas', KelasController::class)
            ->only(['create', 'store'])
            ->middleware('permission:kelas.create')
            ->parameters(['kelas' => 'kls']);

        Route::resource('kelas', KelasController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:kelas.edit')
            ->parameters(['kelas' => 'kls']);

        Route::resource('kelas', KelasController::class)
            ->only(['destroy'])
            ->middleware('permission:kelas.delete')
            ->parameters(['kelas' => 'kls']);
    });

    // CRUD Jadwal Kuliah
    Route::middleware('permission:menu-jadwal-kuliah')->group(function () {
        Route::resource('jadwal-kuliah', JadwalKuliahController::class)
            ->only(['index', 'show'])
            ->middleware('permission:jadwal-kuliah.view');

        Route::resource('jadwal-kuliah', JadwalKuliahController::class)
            ->only(['create', 'store'])
            ->middleware('permission:jadwal-kuliah.create');

        Route::resource('jadwal-kuliah', JadwalKuliahController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:jadwal-kuliah.edit');

        Route::resource('jadwal-kuliah', JadwalKuliahController::class)
            ->only(['destroy'])
            ->middleware('permission:jadwal-kuliah.delete');
    });

    // Pengelolaan Role dan Pengguna hanya untuk admin.
    Route::middleware('role:admin')->group(function () {
        Route::resource('role', RoleController::class)->except('show');
        Route::resource('pengguna', UserController::class)->except('show');
    });
});
