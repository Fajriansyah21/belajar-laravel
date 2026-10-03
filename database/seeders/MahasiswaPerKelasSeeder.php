<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Mahasiswa;

class MahasiswaPerKelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = [
            'ase11' => [
                'kode' => '1',
                'angkatan' => 2026,
                'jurusan' => 'Informatika',
            ],
            'ase12' => [
                'kode' => '2',
                'angkatan' => 2026,
                'jurusan' => 'Informatika',
            ],
        ];

        foreach ($kelasList as $namaKelas => $meta) {
            $kelas = Kelas::firstOrCreate(
                ['nama_kelas' => $namaKelas],
                [
                    'angkatan' => $meta['angkatan'],
                    'jurusan' => $meta['jurusan'],
                ]
            );

            for ($i = 1; $i <= 25; $i++) {
                $nim = '2026' . $meta['kode']
                    . str_pad((string) $i, 3, '0', STR_PAD_LEFT);

                Mahasiswa::firstOrCreate(
                    ['nim' => $nim],
                    [
                        'nama' => fake()->name(),
                        'jurusan' => $meta['jurusan'],
                        'email' => fake()->unique()->safeEmail(),
                        'angkatan' => $meta['angkatan'],
                        'kelas_id' => $kelas->id,
                    ]
                );
            }
        }
    }
}