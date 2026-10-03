@auth
    <span class="user-info">
        {{ auth()->user()->name }}
        ({{ auth()->user()->getRoleNames()->implode(', ') }})
    </span>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endauth
