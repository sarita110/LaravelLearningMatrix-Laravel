<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        Laravel <span>Learning Matrix</span>
    </a>

    <div class="navbar-links">
        <a href="{{ route('concepts.index') }}">Browse Concepts</a>

        @for($i = 1; $i <= 7; $i++)
            <a href="{{ route('concepts.index', ['phase' => $i]) }}">{{ $i === 7 ? 'Advanced' : 'Phase '.$i }}</a>
        @endfor

        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users') }}" style="color: #FFC107; font-weight: 700;">⚙ Admin</a>
            @endif
            <a href="{{ route('concepts.create') }}" class="btn-nav">+ Add Concept</a>

            {{-- Profile avatar + name link --}}
            <a href="{{ route('profile.show') }}"
               style="display:inline-flex; align-items:center; gap:7px; text-decoration:none; color:#AACCFF;">
                <img src="{{ auth()->user()->avatarUrl() }}"
                     alt="{{ auth()->user()->name }}"
                     style="width:28px; height:28px; border-radius:50%; object-fit:cover; border:2px solid #5BA4E0;">
                <span style="font-size:.88rem;">{{ auth()->user()->name }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;color:#AACCFF;cursor:pointer;font-size:.9rem;">
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-nav">Login</a>
        @endauth
    </div>
</nav>
