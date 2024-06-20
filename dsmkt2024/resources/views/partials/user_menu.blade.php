<div class="menu-container">
    @php $renderedIds = []; @endphp
    <ul class="menu-list">
        @can('view_reports')
            <li><h2><a href="{{ route('reports.index') }}">Reports</a></h2></li>
        @endcan
        @foreach($menuItems as $menuItem)
            <li class="menu-item">
                <h2><a href="{{ route('user-menu.files', $menuItem->id) }}">{{ $menuItem->name }}</a></h2>
                @if($menuItem->children->isNotEmpty())
                    <ul class="sub-menu">
                        @foreach($menuItem->children as $child)
                            @include('partials.menu_item', ['menuItem' => $child])
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
