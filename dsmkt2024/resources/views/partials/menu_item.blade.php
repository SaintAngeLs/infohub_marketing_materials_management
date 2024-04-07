<li>
    <h2><a href="{{ route('user-menu.files', $menuItem->id) }}">{{ $menuItem->name }}</a></h2>
    @if($menuItem->children->isNotEmpty())
        <ul>
            @foreach($menuItem->children as $child)
                {{-- Directly include the partial for the child item --}}
                @include('partials.menu_item', ['menuItem' => $child])
            @endforeach
        </ul>
    @endif
</li>
