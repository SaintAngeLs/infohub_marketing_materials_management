@php
    $isEdit = isset($group);
    $formAction = $isEdit ? route('menu.users.group.update', $group->id) : route('menu.users.group.store');
    $submitButtonText = $isEdit ? 'Zaktualizuj Grupę' : 'Dodaj Grupę';
@endphp

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PATCH')
        <input type="hidden" id="group-id" name="group_id" value="{{ $group->id }}">
    @endif

    <div class="form-group">
        <label for="name">Nazwa Grupy</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $group->name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label>Uprawnienia</label>
        @if($isEdit)
            <a href="{{ route('menu.users.group.permissions.edit', $group->id) }}" class="btn btn-primary">Edytuj uprawnienia</a>
        @else
            <p class="text-muted">Uprawnienia będą dostępne po zapisaniu grupy.</p>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">{{ $submitButtonText }}</button>
</form>
