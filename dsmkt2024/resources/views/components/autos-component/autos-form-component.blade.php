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

    <div class="mt-6 flex justify-between">
        <div class="table-button">
            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="btn">{{ $isEdit ? 'Zaktualizuj' : 'Dodaj' }}</a>
        </div>
        <div class="table-button-2">
            <a href="{{ route('menu.autos.index') }}" class="btn">Anuluj</a>
        </div>
    </div>
</form>
