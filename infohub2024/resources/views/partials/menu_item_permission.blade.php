@php
    $paddingLeft = $depth * 20;
@endphp

<tr>
    <td style="padding-left: {{ $paddingLeft }}px;">{{ $menuItem['name'] }}</td>
    <td>
        <input type="checkbox" name="menu_permissions[]" value="{{ $menuItem['id'] }}" {{ $menuItem['checked'] ? 'checked' : '' }}>
    </td>
</tr>

@if(!empty($menuItem['children']))
    @foreach($menuItem['children'] as $childMenuItem)
        @include('partials.menu_item_permission', ['menuItem' => $childMenuItem, 'depth' => $depth + 1])
    @endforeach
@endif
