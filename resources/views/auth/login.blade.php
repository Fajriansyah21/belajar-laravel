<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        @include('partials.style')
        .login-box { max-width: 400px; margin: 60px auto; padding: 24px; border: 1px solid #999; border-radius: 6px; background: #fafafa; }
        .login-box h1 { margin-top: 0; font-size: 22px; }
        .login-box form { display: block; }
        .login-box .field { margin-bottom: 16px; }
        .login-box button { width: 100%; }
        .remember { font-weight: normal; }
        .hint { margin-top: 16px; font-size: 13px; color: #555; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Login</h1>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="field">
                <label for="login">Email atau Username</label>
                <input type="text" id="login" name="login" value="{{ old('login') }}" autocomplete="username" required autofocus>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <div class="field">
                <label class="remember"><input type="checkbox" name="remember"> Ingat saya</label>
            </div>
            <button type="submit">Masuk</button>
        </form>

        <p class="hint">
            Akun uji coba bawaan (jika seed sebelumnya sudah dijalankan):<br>
            admin@example.test / password<br>
            mahasiswa@example.test / password
        </p>
    </div>
</body>
</html>
