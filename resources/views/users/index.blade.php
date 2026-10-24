<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <style>
        @include('partials.style')
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .role {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 12px;
            background: #e8eefc;
            color: #254a91;
            font-size: 12px;
        }

        .actions {
            white-space: normal;
        }

        .actions .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .danger {
            color: #a40000;
        }

        @media (max-width: 700px) {
            .table-wrap {
                overflow-x: auto;
            }

            table {
                min-width: 700px;
            }
        }
    </style>
</head>

<body>
    @include('partials.nav')

    <div class="page-header">
        <div>
            <h1>Manajemen Pengguna</h1>
            <p>Kelola akun yang dapat masuk ke aplikasi.</p>
        </div>
        <a class="btn" href="{{ route('users.create') }}">+ Tambah Pengguna</a>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $user->username ?? $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse ($user->getRoleNames() as $role)
                                <span class="role">{{ $role }}</span>
                            @empty
                                <span>-</span>
                            @endforelse
                        </td>

                        <td class="actions">
                            <div class="action-buttons">
                                <a class="btn" href="{{ route('users.edit', $user) }}">
                                    Ubah
                                </a>

                                @if (!auth()->user()->is($user))
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->username ?? $user->name }}?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="danger" type="submit">Hapus</button>
                                    </form>
                                @else
                                    <span>(akun aktif)</span>
                                @endif
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada akun pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links('pagination.custom') }}
    <p><a href="{{ route('mahasiswa.index') }}">Kembali ke halaman data</a></p>
</body>

</html>