{{-- components/file-form-component.blade.php --}}

<div class="file-upload-component">
    <h2 class="text-xl font-semibold mb-4">Pliki / Nowy Plik</h2>

    <form action="{{ route('menu.files.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        {{-- Menu Item Selection --}}
        <div class="mb-3">
            <label for="menu_item_id" class="form-label">Zakładka</label>
            <select id="menu_item_id" name="menu_item_id" class="form-select">
                <option value="">Wybierz zakładkę</option>
                @foreach($menuItemsToSelect as $menuItem)
                    <option value="{{ $menuItem->id }}">{{ $menuItem->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- File Name --}}
        <div class="mb-3">
            <label for="file_name" class="form-label">Nazwa Pliku*</label>
            <input type="text" id="file_name" name="name" class="form-control" required>
        </div>

        {{-- File Location --}}
        <div class="mb-3">
            <label class="form-label">Lokalizacja Pliku*</label>
            <div>
                <input type="radio" name="file_location" value="disk" id="location_disk" checked>
                <label for="location_disk">import pliku z dysku</label>
            </div>
            <div>
                <input type="radio" name="file_location" value="external" id="location_external">
                <label for="location_external">plik z zewnętrznego serwera</label>
            </div>
            <div>
                <input type="radio" name="file_location" value="server" id="location_server">
                <label for="location_server">wskaż plik uprzednio wgrany na serwer</label>
            </div>
        </div>

        {{-- File Upload --}}
        <div class="mb-3">
            <label for="file" class="form-label">Plik*</label>
            <input type="file" id="file" name="file" class="form-control" required>
        </div>

        {{-- Visibility Start --}}
        <div class="mb-3">
            <label for="visible_from" class="form-label">Widoczny Od</label>
            <input type="date" id="visible_from" name="visible_from" class="form-control">
        </div>

        {{-- Visibility End --}}
        <div class="mb-3">
            <label for="visible_to" class="form-label">Widoczny Do</label>
            <input type="date" id="visible_to" name="visible_to" class="form-control">
        </div>

        {{-- Keywords --}}
        <div class="mb-3">
            <label for="tags" class="form-label">Słowa Kluczowe</label>
            <input type="text" id="tags" name="tags" class="form-control" placeholder="(oddzielone spacją)">
        </div>

        {{-- Car Association --}}
        <div class="mb-3">
            <label for="auto_id" class="form-label">Dotyczy Samochodu</label>
            <select id="auto_id" name="auto_id" class="form-select">
                <option value="">Wybierz samochód</option>
                @foreach($autos as $auto)
                    <option value="{{ $auto->id }}">{{ $auto->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Submit Button --}}
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Dodaj Plik</button>
        </div>
    </form>
</div>

