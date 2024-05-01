@php
    $isEdit = isset($file);
    $formAction = $isEdit ? route('menu.users.group.update', $file->id) : route('menu.users.group.store');
    $submitButtonText = $isEdit ? 'Zaktualizuj Plik' : 'Dodaj Plik';
@endphp

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PATCH')
        <input type="hidden" id="group-id" name="group_id" value="{{ $group->id }}">
    @endif
    <input type="hidden" id="group-id" name="group_id" value="{{ $group->id }}">
    <div class="form-group">
        <label for="name">Nazwa Grupy</label>

        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $group->name ?? '') }}" required>
    </div>

    <div class="form-group">
        Uprawnienia
    </div>

    <div class="menu-tree-component" id="menu-tree-permissions"></div>

    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Aktualizuj Grupę' : 'Dodaj Grupę' }}</button>
</form>
