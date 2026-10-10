@auth
    <nav class="main-nav">
        <div class="nav-links">
            @role('admin')
                <a href="{{ route('users.index') }}">Manajemen Pengguna</a>
            @endrole
        </div>
        <div class="nav-user">
            <span class="user-info">
                {{ auth()->user()->username ?? auth()->user()->name }}
                ({{ auth()->user()->getRoleNames()->implode(', ') }})
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>
@endauth
