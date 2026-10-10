<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Role</title>

    <style>
        @include('partials.style')

        body {
            max-width: 800px;
        }

        .permission-group {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .permission-group h3 {
            margin-top: 0;
            margin-bottom: 12px;
        }

        .permission-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
        }

        .permission-item input {
            margin: 0;
        }
    </style>
</head>
<body>
    @include('partials.nav')

    <h1>Tambah Role</h1>

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('role.store') }}" method="POST">
        @csrf

        <p>
            <label for="name">Nama Role</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                maxlength="50"
                required
            >
        </p>

        <h2>Pengaturan Permission</h2>
        <p>Pilih hak akses yang akan diberikan kepada role ini.</p>

        @foreach ($permissionGroups as $groupName => $groupPermissions)
            @if (count($groupPermissions) > 0)
                <div class="permission-group">
                    <h3>{{ $groupName }}</h3>

                    <div class="permission-list">
                        @foreach ($groupPermissions as $permission)
                            <label class="permission-item">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission['name'] }}"
                                    @checked(in_array(
                                        $permission['name'],
                                        old('permissions', [])
                                    ))
                                >

                                <span>{{ $permission['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <button type="submit">Simpan</button>
        <a href="{{ route('role.index') }}" class="btn">Batal</a>
    </form>
</body>
</html>
