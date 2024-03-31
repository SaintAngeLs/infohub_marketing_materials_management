<h2><a href="{{ route('menu.concessions.index') }}">Koncesje</a></h2>
<h2><a href="{{ route('menu.users') }}">Użytkownicy</a></h2>
<ul>
    <li><a href="{{ route('menu.users.groups')}}">grupy</a></li>
    <li><a href="{{ route('menu.users.applications.view')}}">zgłoszenia</a></li>
</ul>
<h2><a href="{{ route('menu') }}">Struktura menu</a></h2>
<h2><a href="{{ route('menu.autos') }}">Samochody</a></h2>
<h2><a href="{{ route('menu.files') }}">Pliki</a></h2>
{{-- @can('view_reports') --}}
<h2><a href="{{ route('menu.statistics') }}">Statystyki</a></h2>
{{-- @endcan --}}
<h2><a href="{{ route('menu.reports') }}">Raporty</a></h2>

{{--
<ul>
    <li><a href="{{ route('dashboard') }}">Koncesje</a></li>
    <li><a href="{{ route('users') }}">Użytkownicy</a></li>
    <li><a href="{{ route('menu') }}">Struktura menu</a></li>
    <li><a href="{{ route('menu') }}">Samochody</a></li>
    <li><a href="{{ route('menu') }}">Pliki</a></li>
    @can('view_reports')
    <li><a href="{{ route('reports.index') }}">Statystyki</a></li>
    @endcan
</ul> --}}
