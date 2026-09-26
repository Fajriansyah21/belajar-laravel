<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        Mahasiswa::create([
            'nim' => '20260001',
            'nama' => 'Fajriansyah Pratama',
            'jurusan' => 'ASE 25',
            'email' => 'Fajriansyah@example.test',
            'angkatan' => 2025,
        ]);

        Mahasiswa::create([
            'nim' => '20260002',
            'nama' => 'Heru Rahmat Anugerah',
            'jurusan' => 'ASE 25',
            'email' => 'heru@example.test',
            'angkatan' => 2025,
        ]);

        Mahasiswa::create([
            'nim' => '20260003',
            'nama' => 'Daffa Ramadhan',
            'jurusan' => 'ASE 25',
            'email' => 'daffa@example.test',
            'angkatan' => 2025,
        ]);

        Mahasiswa::create([
            'nim' => '20260004',
            'nama' => 'Davinci',
            'jurusan' => 'ASE 25',
            'email' => 'davinci@example.test',
            'angkatan' => 2025,
        ]);

        Mahasiswa::create([
            'nim' => '20260005',
            'nama' => 'Leopard',
            'jurusan' => 'ASE 25',
            'email' => 'leopard@example.test',
            'angkatan' => 2025,
        ]);
    }
}