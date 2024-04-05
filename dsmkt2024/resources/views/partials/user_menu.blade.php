<ul>
    <li><h2><a href="{{ route('dashboard') }}">Dashboard</a></h2></li>
    @can('view_reports')
    <li><a href="{{ route('reports.index') }}">Reports</a></li>
    @endcan

    @forelse($menuItems as $menuItem)
        {{-- <li><a href="{{ route($menuItem->slug) }}">{{ $menuItem->name }}</a></li> --}}
        <li>
            <h2><a href="{{ route('user-menu.files', $menuItem->id) }}">{{ $menuItem->name }}</h2></a>
        </li>
    @empty
        no items to display
    @endforelse
</ul>
