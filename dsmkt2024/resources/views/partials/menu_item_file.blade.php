<tr>
    <td>{{ $menuItem['name'] }}</td>
    <td>{{ $menuItem['status'] }}</td>
    <td>{{ $menuItem['owners'] }}</td>
    <td>{{ $menuItem['visibility'] }}</td>
    <td>Pliki</td>
</tr>
<tr>
    <td colspan="5">
        @if(count($menuItem['files']) > 0)
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Status</th>
                    <th>Nazwa</th>
                    <th>Rozszerzenie</th>
                    <th>Rozmiar</th>
                    <th>Widoczność</th>
                    <th>Ostatnia aktualizacja</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody>
                @foreach($menuItem['files'] as $file)
                    <tr>
                        <td>{{ $file['status'] }}</td>
                        <td><a href="#" class="file-link" data-file-id="{{ $file['id'] }}">{{ $file['name'] }}</a></td>
                        <td>{{ $file['extension'] }}</td>
                        <td>{{ $file['size'] }}</td>
                        <td>{{ $file['visibility'] }}</td>
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
        @include('partials.menu_item_file', ['menuItem' => $childMenuItem])
    @endforeach
@endif
