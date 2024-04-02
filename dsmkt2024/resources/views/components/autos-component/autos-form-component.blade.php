@php
    $isEdit = isset($auto) && $auto->id;
    $formAction = $isEdit ? route('menu.autos.update', $auto->id) : route('menu.autos.store');
    $formMethod = $isEdit ? 'PATCH' : 'POST';
@endphp

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PATCH') @endif

    <div class="form-group">
        <label for="name">Nazwa*</label>
        <input type="text" name="name" id="name" value="{{ old('name', $auto->name ?? '') }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Zaktualizuj' : 'Dodaj' }}</button>
    <a href="{{ route('menu.autos.index') }}" class="btn btn-secondary">Anuluj</a>
</form>
