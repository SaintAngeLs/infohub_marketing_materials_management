<ul>
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    @can('view_reports')
    <li><a href="{{ route('reports.index') }}">Reports</a></li>
    @endcan

    @forelse($menuItems as $menuItem)
        {{-- <li><a href="{{ route($menuItem->slug) }}">{{ $menuItem->name }}</a></li> --}}
        <li>
            <a href="{{ route('user-menu.files', $menuItem->id) }}">{{ $menuItem->name }}</a>
        </li>
    @empty
        no items to display
    @endforelse
</ul>
