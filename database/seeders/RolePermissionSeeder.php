<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permission dasar
        $permissions = [
            'lihat-data',
            'tambah-data',
            'ubah-data',
            'hapus-data',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // Role admin -> semua permission
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions($permissions);

        // Role mahasiswa -> hanya lihat
        $roleMhs = Role::firstOrCreate(['name' => 'mahasiswa']);
        $roleMhs->syncPermissions(['lihat-data']);

        // User default
        // Password di-hash otomatis oleh cast 'hashed'
        User::firstOrCreate(
            ['email' => 'admin@example.test'],
            [
                'name' => 'Administrator',
                'password' => 'password',
            ]
        )->syncRoles(['admin']);

        User::firstOrCreate(
            ['email' => 'mahasiswa@example.test'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => 'password',
            ]
        )->syncRoles(['mahasiswa']);
    }
}