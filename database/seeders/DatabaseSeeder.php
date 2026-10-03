<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat role, permission, dan akun demo.
        $this->call(RolePermissionSeeder::class);

        // Membuat kelas dan 50 data mahasiswa.
        $this->call(MahasiswaPerKelasSeeder::class);
    }
}
