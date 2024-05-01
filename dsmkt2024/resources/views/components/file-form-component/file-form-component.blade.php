{{-- Determine if we're adding a new file or editing an existing one --}}
@php
    $isEdit = isset($file);
    $formAction = $isEdit ? route('menu.file.update', $file->id) : route('menu.files.store');
    $submitButtonText = $isEdit ? 'Zaktualizuj Plik' : 'Dodaj Plik';
@endphp



<div class="file-upload-component" id="file-upload-component">
    {{-- <h2 class="text-xl font-semibold mb-4">{{ $isEdit ? 'Edytuj Plik' : 'Pliki / Nowy Plik' }}</h2> --}}

    <form action="{{ $formAction }}" method="post" enctype="multipart/form-data">
        @csrf
        @if($isEdit) @method('PATCH') @endif

        <div class="row">
            <div class="col">
                {{-- Menu Item Selection --}}
                <div class="mb-3  form-group">
                    {{-- <label for="menu_id" class="form-label">Zakładka</label>
                    <select id="menu_id" name="menu_id" class="form-select">
                        <option value="">Wybierz zakładkę</option>
                        @foreach($menuItemsToSelect as $menuItem)
                            <option value="{{ $menuItem->id }}" {{ $isEdit && $file->menu_id == $menuItem->id ? 'selected' : '' }}>{{ $menuItem->name }}</option>
                        @endforeach
                    </select> --}}

                    @if(isset($selectedMenuItemId))
                        @php
                            $selectedMenuItem = $menuItemsToSelect->firstWhere('id', $selectedMenuItemId);
                        @endphp
                        <label for="menu_id" class="form-label">Zakładka</label>
                        <input type="hidden" name="menu_id" value="{{ $selectedMenuItemId }}">
                        <p class="form-control-plaintext">{{ $selectedMenuItem->name ?? 'Menu Item Not Found' }}</p>
                        <div class="invalid-feedback"></div>
                    @else
                        <label for="menu_id" class="form-label">Zakładka</label>
                        <select id="menu_id" name="menu_id" class="form-select">
                            <option value="">Wybierz zakładkę</option>
                            @foreach($menuItemsToSelect as $menuItem)
                                <option value="{{ $menuItem->id }}">{{ $menuItem->name }}</option>
                            @endforeach
                        </select>
                    @endif

                </div>

                {{-- File Name --}}
                <div class="mb-3 form-group">
                    <label for="file_name" class="form-label">Nazwa Pliku*</label>
                    <input type="text" id="file_name" name="name" class="form-control" required value="{{ $isEdit ? $file->name : '' }}">
                    <div class="invalid-feedback"></div>
                </div>

                {{-- File Location --}}
                {{-- File Source Selection --}}
                <div class="mb-3 form-group">
                    <label class="form-label">Lokalizacja pliku:</label>
                    <select name="file_source" id="file_source" class="form-select">
                        <option value="file_pc">import pliku z dysku</option>
                        <option value="file_external">plik z zewnętrznego serwera</option>
                        <option value="file_server">wskaż plik uprzednio wgrany na serwer</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                  {{-- File Upload Dropzone Area --}}
                <div class="mb-3 form-group" id="input_file_pc">
                    <label for="dropzoneFileUpload" class="form-label">Plik*</label>
                    <div id="dropzoneFileUpload" name="file" class="dropzone"></div>
                </div>

                {{-- Inputs for each file source --}}
                {{-- <div  class="file-source">
                    <input type="file" name="file_pc" />
                </div> --}}
                <div class="file-source form-group" style="display: none;" id="input_file_external">
                    <input type="text" name="file_url" placeholder="URL pliku" />
                </div>
                <div class="file-source" style="display: none;" id="input_server_file">
                    <button type="button" id="browseServerFilesButton" class="btn btn-primary">Przeglądaj zasoby serwera</button>
                    @include('components.file-form-component.serwer-upload-modal')
                    <input type="text" id="selectedFileName" class="form-control mt-2" readonly placeholder="Nazwa wybranego pliku">
                    <input type="hidden" name="server_file" id="server_file_input" value="">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col">
                {{-- Visibility Start --}}
                <div class="mb-3 form-group">
                    <label for="start" class="form-label">Widoczny Od</label>
                    <input type="date" id="start" name="start" class="form-control" value="{{ $isEdit ? optional($file->start)->format('Y-m-d') : '' }}">
                    <div class="invalid-feedback"></div>
                </div>

                {{-- Visibility End --}}
                <div class="mb-3 form-group">
                    <label for="end" class="form-label">Widoczny Do</label>
                    <input type="date" id="end" name="end" class="form-control" value="{{ $isEdit ? optional($file->end)->format('Y-m-d') : '' }}">
                    <div class="invalid-feedback"></div>
                </div>

                {{-- Keywords --}}
                <div class="mb-3 form-group">
                    <label for="key_words" class="form-label">Słowa Kluczowe</label>
                    <input type="text" id="key_words" name="key_words" class="form-control" placeholder="(oddzielone spacją)" value="{{ $isEdit ? $file->key_words : '' }}">
                    <div class="invalid-feedback"></div>
                </div>

                {{-- Car Association --}}
                <div class="mb-3 form-group">
                    <label for="auto_id" class="form-label">Dotyczy Samochodu</label>
                    <select id="auto_id" name="auto_id" class="form-select">
                        <option value="">Wybierz samochód</option>
                        @foreach($autos as $auto)
                            <option value="{{ $auto->id }}" {{ $isEdit && $file->auto_id == $auto->id ? 'selected' : '' }}>{{ $auto->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        {{-- Submit and Delete Buttons --}}
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">{{ $submitButtonText }}</button>
            @if($isEdit)
                <button type="button" class="btn btn-danger" id="deleteFileButton">Usuń Plik</button>
            @endif
        </div>
    </form>
</div>

{{-- Scripts section for the dropzon adn file uploader --}}
<script>
    @if($isEdit && $file->path)
        var existingFileUrl = "{{ Storage::url($file->path) }}";
        var existingFileName = "{{ $file->name }}";
    @else
        var existingFileUrl = '';
        var existingFileName = '';
    @endif
</script>
