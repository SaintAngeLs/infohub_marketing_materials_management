@php
    $depth = isset($depth) ? $depth : 0;
@endphp

<tr class="menu-item-row">
    <td class="py-3" style="padding-left: {{ $depth * 20 }}px;">{{ $menuItem['name'] }}</td>
    <td class="py-3">{{ $menuItem['status'] }}</td>
    <td class="py-3">{!! $menuItem['owners'] !!}</td>
    <td class="py-3">{{ $menuItem['visibility'] }}</td>
    <td class="toggle-files py-3" data-toggle="files-{{ $menuItem['id'] }}">Pliki</td>
</tr>
<tr class="files-row" id="files-{{ $menuItem['id'] }}">
    <td colspan="5">
        @if(count($menuItem['files']) > 0)
            <table class="table file-table">
                <thead>
                <tr>
                    <th class="py-4">Status</th>
                    <th class="py-4">Nazwa</th>
                    <th class="py-4">Rozszerzenie</th>
                    <th class="py-4">Rozmiar</th>
                    <th class="py-4">Ostatnia aktualizacja</th>
                    <th class="py-4">Akcje</th>
                </tr>
                </thead>
                <tbody>
                @foreach($menuItem['files'] as $file)
                    <tr>
                        <td class="py-4">
                            <button class="btn btn-sm toggle-file-status" data-file-id="{{ $file['id'] }}">
                                {{ $file['status'] ? 'Aktywny' : 'Nieaktywny' }}
                            </button>
                        </td class="py-4">
                        <td class="py-4"><a href="#" class="file-link" data-file-id="{{ $file['id'] }}">{{ $file['name'] }}</a></td>
                        <td class="py-4">{{ $file['extension'] }}</td>
                        <td class="py-4">{{ $file['size'] }}</td>
                        <td class="py-4">{{ $file['lastUpdate'] }}</td>
                        <td class="py-4">
                            <button onclick="downloadFile({{ $file['id'] }})" class="btn btn-sm download-file-btn">pobierz</button>
                            <button onclick="deleteFile({{ $file['id'] }})" class="btn btn-sm btn-danger delete-file-btn">usuń</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            Brak plików
        @endif
    </td>
</tr>
@if(!empty($menuItem['children']))
    @foreach($menuItem['children'] as $childMenuItem)
        @include('partials.menu_item_file', ['menuItem' => $childMenuItem, 'depth' => $depth + 1])
    @endforeach
@endif
