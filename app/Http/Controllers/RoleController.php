<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * Menampilkan seluruh role.
     */
    public function index(): View
    {
        $roles = Role::withCount('users')
            ->with('permissions')
            ->orderBy('id')
            ->get();

        return view('role.index', compact('roles'));
    }

    /**
     * Menampilkan form tambah role.
     */
    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();
        $permissionGroups = $this->getPermissionGroups($permissions);

        return view('role.create', compact(
            'permissions',
            'permissionGroups'
        ));
    }

    /**
     * Menyimpan role baru beserta permission-nya.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'name'),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                'distinct',
                Rule::exists('permissions', 'name'),
            ],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('role.index')
            ->with('success', 'Data role berhasil ditambahkan.');
    }

    /**
     * Menampilkan form ubah role.
     */
    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('name')->get();
        $permissionGroups = $this->getPermissionGroups($permissions);

        return view('role.edit', compact(
            'role',
            'permissions',
            'permissionGroups'
        ));
    }

    /**
     * Memperbarui role beserta permission-nya.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                'distinct',
                Rule::exists('permissions', 'name'),
            ],
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('role.index')
            ->with('success', 'Data role berhasil diubah.');
    }

    /**
     * Menghapus role jika tidak sedang digunakan.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->users()->exists()) {
            return redirect()
                ->route('role.index')
                ->with(
                    'error',
                    'Role tidak bisa dihapus karena masih dipakai oleh pengguna.'
                );
        }

        $role->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('role.index')
            ->with('success', 'Data role berhasil dihapus.');
    }

    /**
     * Mengelompokkan permission CRUD berdasarkan menu.
     */
    private function getPermissionGroups($permissions): array
    {
        $resources = [
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'mata-kuliah' => 'Mata Kuliah',
            'ruangan' => 'Ruangan',
            'kelas' => 'Kelas',
            'jadwal-kuliah' => 'Jadwal Kuliah',
        ];

        $actions = [
            'view' => 'Lihat',
            'create' => 'Tambah',
            'edit' => 'Ubah',
            'delete' => 'Hapus',
        ];

        $permissionGroups = [];

        foreach ($resources as $resource => $label) {
            $permissionGroups[$label] = [];

            foreach ($actions as $action => $actionLabel) {
                $permissionName = $resource . '.' . $action;

                $permission = $permissions->firstWhere(
                    'name',
                    $permissionName
                );

                if ($permission) {
                    $permissionGroups[$label][] = [
                        'name' => $permission->name,
                        'label' => $actionLabel,
                    ];
                }
            }
        }

        // Permission umum dan permission menu yang masih digunakan.
        $otherPermissions = $permissions->filter(function ($permission) use ($resources) {
            foreach (array_keys($resources) as $resource) {
                if (str_starts_with($permission->name, $resource . '.')) {
                    return false;
                }
            }

            return true;
        })->values();

        $permissionGroups['Permission Lainnya'] = $otherPermissions
            ->map(function ($permission) {
                return [
                    'name' => $permission->name,
                    'label' => $permission->name,
                ];
            })
            ->all();

        return $permissionGroups;
    }
}
