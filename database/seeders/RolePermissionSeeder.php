<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['lihat-data', 'tambah-data', 'ubah-data', 'hapus-data'];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleAdmin->syncPermissions($permissions);

        $roleDosen = Role::firstOrCreate(['name' => 'dosen', 'guard_name' => 'web']);
        $roleDosen->syncPermissions(['lihat-data']);

        $roleMahasiswa = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);
        $roleMahasiswa->syncPermissions(['lihat-data']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.test'],
            [
                'name' => 'Administrator',
                'password' => 'password',
            ]
        );
        if (empty($admin->username)) {
            $admin->username = 'administrator';
            $admin->save();
        }
        $admin->syncRoles(['admin']);

        $mahasiswa = User::firstOrCreate(
            ['email' => 'mahasiswa@example.test'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => 'password',
            ]
        );
        if (empty($mahasiswa->username)) {
            $mahasiswa->username = 'mahasiswa-demo';
            $mahasiswa->save();
        }
        $mahasiswa->syncRoles(['mahasiswa']);
    }
}
