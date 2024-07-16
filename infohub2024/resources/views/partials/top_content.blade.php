<div class="navigation">
    @auth
        <div class="navigation-inner">
            <div id="logo">
                <a href="/dashboard"></a>
            </div>
            <div class="user-info">

                <a href="{{ route('user.my-account') }}">
                    <img src="{{ asset('img/icons/user-logo.svg') }}" alt="User Icon" />
                    Moje konto
                </a>

                <p>

                    Zalogowany jako: <span>{{ Auth::user()->email }}</span>
                </p>
                 @if (Auth::user()->isAdmin())
                    @if (request()->routeIs('menu'))
                        <a href="{{ route('dashboard') }}" class="active">
                            <img src="{{ asset('img/icons/user-logo.svg') }}" alt="User Panel Icon" />
                            Panel Użytkownika
                        </a>
                    @else
                        <a href="{{ route('menu') }}">
                            <img src="{{ asset('img/icons/user-admin-logo.svg') }}" alt="Admin Panel Icon" />
                            Panel Administracyjny
                        </a>
                    @endif
                @endif

                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="{{ asset('img/icons/log-out.svg') }}" alt="Log Out Icon" />
                    Wyloguj
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    @else
        <div id="logo-center" class="center-logo">
            <a href="/"></a>
        </div>
    @endauth
</div>
<script>
function searchMe() {
    var q = $.trim($('#search').val());
    if(q.length < 2) {
        alert('Proszę wpisać co najmniej 2 znaki');
        return false;
    }
    window.location.href = '/search/' + q;
    return false;
}
</script>

