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

        $permissions = ['lihat-data', 'tambah-data', 'ubah-data', 'hapus-data'];
        $menuPermissions = [
            'menu-mahasiswa',
            'menu-dosen',
            'menu-mata-kuliah',
            'menu-ruangan',
            'menu-kelas',
            'menu-jadwal-kuliah',
        ];

        foreach (array_merge($permissions, $menuPermissions) as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleAdmin->syncPermissions(array_merge($permissions, $menuPermissions));

        $roleMahasiswa = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);
        $roleMahasiswa->syncPermissions(['lihat-data', 'menu-jadwal-kuliah']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.test'],
            ['name' => 'Administrator', 'password' => 'password']
        );
        $admin->syncRoles(['admin']);

        $mahasiswa = User::firstOrCreate(
            ['email' => 'mahasiswa@example.test'],
            ['name' => 'Mahasiswa Demo', 'password' => 'password']
        );
        $mahasiswa->syncRoles(['mahasiswa']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
