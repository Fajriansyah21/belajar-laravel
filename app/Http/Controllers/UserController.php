<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private const ROLES = ['admin', 'dosen', 'mahasiswa'];

    public function index(): View
    {
        $users = User::with('roles')->orderBy('username')->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = self::ROLES;

        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:100', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(self::ROLES)],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($validated): void {
            $user = User::create([
                'username' => $validated['username'],
                // Field name tetap dipakai oleh tampilan project yang sudah ada.
                'name' => $validated['username'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            Role::findOrCreate($validated['role'], 'web');
            $user->syncRoles([$validated['role']]);
        });

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $roles = self::ROLES;
        $currentRole = $user->getRoleNames()->first();

        return view('users.edit', compact('user', 'roles', 'currentRole'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'username' => [
                'required', 'string', 'min:3', 'max:100', 'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(self::ROLES)],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($user->is(auth()->user()) && $validated['role'] !== 'admin') {
            return back()
                ->withErrors(['role' => 'Role akun yang sedang digunakan tidak boleh diubah dari admin.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        if ($user->hasRole('admin') && $validated['role'] !== 'admin' && User::role('admin')->count() <= 1) {
            return back()
                ->withErrors(['role' => 'Role admin terakhir tidak dapat diturunkan. Buat admin lain terlebih dahulu.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        DB::transaction(function () use ($validated, $user): void {
            $user->username = $validated['username'];
            $user->name = $validated['username'];
            $user->email = $validated['email'];

            // Password kosong saat edit berarti password lama tetap dipakai.
            if (!empty($validated['password'])) {
                $user->password = $validated['password'];
            }

            $user->save();

            Role::findOrCreate($validated['role'], 'web');
            $user->syncRoles([$validated['role']]);
        });

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')
                ->withErrors(['user' => 'Akun yang sedang digunakan tidak dapat dihapus.']);
        }

        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return redirect()->route('users.index')
                ->withErrors(['user' => 'Admin terakhir tidak dapat dihapus. Buat admin lain terlebih dahulu.']);
        }

        DB::transaction(function () use ($user): void {
            // Bersihkan relasi role sebelum menghapus akun.
            $user->syncRoles([]);
            $user->delete();
        });

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
