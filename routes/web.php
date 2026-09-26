<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get(
//     '/mahasiswa',
//     [MahasiswaController::class, 'index']
// );

Route::resource('mahasiswa', MahasiswaController::class);

Route::resource('dosen', DosenController::class);

Route::resource('mata-kuliah', MataKuliahController::class)
    ->except(['show']);
