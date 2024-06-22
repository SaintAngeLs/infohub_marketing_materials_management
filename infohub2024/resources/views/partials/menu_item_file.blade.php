@php
    $depth = isset($depth) ? $depth : 0;
@endphp

<tr class="menu-item-row">
    <td style="padding-left: {{ $depth * 20 }}px;">{{ $menuItem['name'] }}</td>
    <td>{{ $menuItem['status'] }}</td>
    <td>{{ $menuItem['owners'] }}</td>
    <td>{{ $menuItem['visibility'] }}</td>
    <td class="toggle-files" data-toggle="files-{{ $menuItem['id'] }}">Pliki</td>
</tr>
<tr class="files-row" id="files-{{ $menuItem['id'] }}">
    <td colspan="5">
        @if(count($menuItem['files']) > 0)
            <table class="table file-table">
                <thead>
                <tr>
                    <th>Status</th>
                    <th>Nazwa</th>
                    <th>Rozszerzenie</th>
                    <th>Rozmiar</th>
                    <th>Ostatnia aktualizacja</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody>
                @foreach($menuItem['files'] as $file)
                    <tr>
                        <td>
                            <button class="btn btn-sm toggle-file-status" data-file-id="{{ $file['id'] }}">
                                {{ $file['status'] ? 'Aktywny' : 'Nieaktywny' }}
                            </button>
                        </td>
                        <td><a href="#" class="file-link" data-file-id="{{ $file['id'] }}">{{ $file['name'] }}</a></td>
                        <td>{{ $file['extension'] }}</td>
                        <td>{{ $file['size'] }}</td>
                        <td>{{ $file['lastUpdate'] }}</td>
                        <td>
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
