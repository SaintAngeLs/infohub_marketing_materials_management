<div class="navigation">
    @auth
        <div class="navigation-inner">
            <div id="logo">
                <a href="/dashboard"></a>
            </div>
            <div class="user-info">
                <p>

                    Zalogowany jako: <span>{{ Auth::user()->name }}</span>
                </p>
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">
                        <img src="{{ asset('img/icons/user-admin-logo.svg') }}" alt="Admin Panel Icon" />
                        Panel Administracyjny
                    </a>
                @endif

                <a href="/user/notification">
                    <img src="{{ asset('img/icons/user-logo.svg') }}" alt="User Icon" />
                    Moje konto
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="{{ asset('img/icons/log-out.svg') }}" alt="Log Out Icon" />
                    Wyloguj
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    @else
        <!-- For unauthenticated users, display the logo centrally -->
        <div id="logo-center" class="center-logo">
            <a href="/"></a>
        </div>
    @endauth
</div>
    <!-- Always display the logo -->


    <!-- Display the rest of the content only if authenticated -->
    @auth
        <h1>MATERIAŁY REKLAMOWE DS</h1>
        <div class="clearfix"></div>

        <form id="searchbox" onsubmit="return false;">
            <input id="search" name="query" type="text" placeholder="szukaj" value="">
            <input class="submit" type="submit" value="Szukaj" onclick="searchMe();">
            <div class="clearfix"></div>
        </form>
    @endauth

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



{{--
<div class="navigation">
    @auth
        <div class="navigation-logo flex">
            <div id="logo">
                <a href="/dashboard"></a>
            </div>

            <div id="user-panel">
                <p id="log-out">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Wyloguj</a>
                </p>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

                <p id="my-account"><a href="/user/notification">Moje konto</a></p>

                @if (Auth::user()->isAdmin())
                    <p class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <a id="my-account" href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}">
                            {{ __('Panel Administracyjny') }}
                        </a>
                    </p>
                @endif

                <p>Zalogowany jako: <span>{{ Auth::user()->name }}</span></p>

                <div class="clearfix"></div>
            </div>


        </div>
    @else
        <!-- For unauthenticated users, display the logo centrally -->
        <div id="logo-center" class="center-logo">
            <a href="/"></a>
        </div>
    @endauth

    <!-- Always display the logo -->


    <!-- Display the rest of the content only if authenticated -->
    @auth
        <h1>MATERIAŁY REKLAMOWE DS</h1>
        <div class="clearfix"></div>

        <form id="searchbox" onsubmit="return false;">
            <input id="search" name="query" type="text" placeholder="szukaj" value="">
            <input class="submit" type="submit" value="Szukaj" onclick="searchMe();">
            <div class="clearfix"></div>
        </form>
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
</script> --}}
