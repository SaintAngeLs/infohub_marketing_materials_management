<ul>

    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    @can('view_reports')
    <li><a href="{{ route('reports.index') }}">Reports</a></li>
    @endcan
</ul>
