<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Permission umum yang masih dipertahankan untuk kompatibilitas.
        $permissions = [
            'lihat-data',
            'tambah-data',
            'ubah-data',
            'hapus-data',
        ];

        // Daftar menu yang tersedia.
        $menuPermissions = [
            'menu-mahasiswa',
            'menu-dosen',
            'menu-mata-kuliah',
            'menu-ruangan',
            'menu-kelas',
            'menu-jadwal-kuliah',
        ];

        // Daftar resource dan aksi CRUD khusus setiap menu.
        $resources = [
            'mahasiswa',
            'dosen',
            'mata-kuliah',
            'ruangan',
            'kelas',
            'jadwal-kuliah',
        ];

        $actions = [
            'view',
            'create',
            'edit',
            'delete',
        ];

        // Membuat permission khusus, misalnya mahasiswa.create.
        $resourcePermissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $resourcePermissions[] = $resource . '.' . $action;
            }
        }

        // Gabungkan semua permission yang perlu tersedia.
        $allPermissions = array_merge(
            $permissions,
            $menuPermissions,
            $resourcePermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Role admin tetap memiliki seluruh permission.
        $roleAdmin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $roleAdmin->syncPermissions($allPermissions);

        // Role mahasiswa hanya diberi akses melihat jadwal kuliah.
        $roleMahasiswa = Role::firstOrCreate([
            'name' => 'mahasiswa',
            'guard_name' => 'web',
        ]);

        $roleMahasiswa->syncPermissions([
            'lihat-data',
            'menu-jadwal-kuliah',
            'jadwal-kuliah.view',
        ]);

        // Membuat akun admin demo jika belum tersedia.
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.test'],
            [
                'name' => 'Administrator',
                'password' => 'password',
            ]
        );

        $admin->syncRoles(['admin']);

        // Membuat akun mahasiswa demo jika belum tersedia.
        $mahasiswa = User::firstOrCreate(
            ['email' => 'mahasiswa@example.test'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => 'password',
            ]
        );

        $mahasiswa->syncRoles(['mahasiswa']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
