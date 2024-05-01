@php $renderedIds = []; @endphp
<ul>
    <li><h2><a href="{{ route('dashboard') }}">Dashboard</a></h2></li>
    @can('view_reports')
        <li><a href="{{ route('reports.index') }}">Reports</a></li>
    @endcan

    @foreach($menuItems as $menuItem)
        @include('partials.menu_item', ['menuItem' => $menuItem, 'renderedIds' => &$renderedIds])
    @endforeach
</ul>
