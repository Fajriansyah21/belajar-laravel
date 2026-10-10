<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Pengguna</title>
    <style>
        @include('partials.style')
        .user-form { max-width: 620px; margin: 42px auto; padding: 30px; border: 1px solid #222; background: #fff; }
        .user-form h1 { text-align: center; margin: 0 0 28px; font-size: 25px; font-weight: 500; }
        .field { display: grid; grid-template-columns: 150px minmax(0, 1fr); align-items: start; gap: 14px; margin-bottom: 14px; }
        .field label { padding-top: 10px; font-weight: normal; }
        .field input, .field select { min-height: 44px; background: #4b73c8; border: 2px solid #31579f; color: #fff; padding: 8px 10px; }
        .field select option { background: #fff; color: #222; }
        .field-error { grid-column: 2; color: #a40000; font-size: 13px; margin-top: -8px; }
        .form-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 24px; }
        .hint { color: #555; font-size: 13px; margin: 4px 0 18px; }
        @media (max-width: 550px) { .user-form { padding: 18px; } .field { grid-template-columns: 1fr; gap: 4px; } .field label { padding-top: 0; } .field-error { grid-column: 1; } }
    </style>
</head>
<body>
    @include('partials.nav')
    <main class="user-form">
        <h1>Ubah Pengguna</h1>
        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        @endif
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="username">username</label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username ?? $user->name) }}" autocomplete="username" required minlength="3" maxlength="100">
                @error('username')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="email">email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">password baru</label>
                <input type="password" id="password" name="password" autocomplete="new-password" minlength="8">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password_confirmation">konfirmasi password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" minlength="8">
            </div>
            <p class="hint">Kosongkan bagian password jika tidak ingin mengganti password lama.</p>
            <div class="field">
                <label for="role">role</label>
                <select id="role" name="role" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role', $currentRole) === $role)>{{ $role }}</option>
                    @endforeach
                </select>
                @error('role')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-actions">
                <a class="btn" href="{{ route('users.index') }}">Batal</a>
                <button type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </main>
</body>
</html>
