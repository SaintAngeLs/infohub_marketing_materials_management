@php
    $isEdit = isset($concession);
    $formAction = $isEdit ? route('menu.concessions.update', $concession->id) : route('menu.concessions.store');
@endphp

<form action="{{ $formAction }}" method="POST">
    @csrf
    @if($isEdit) @method('PATCH') @endif

    <div class="concession-component-add">
        <!-- Name -->
        <div class="form-group">
            <label for="name">NAZWA<span class='start-required'>*</span></label>
            <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $concession->name) }}">
        </div>

        <!-- Address -->
        <div class="form-group">
            <label for="address">ADRES<span class='start-required'>*</span></label>
            <input type="text" class="form-control" id="address" name="address" required value="{{ old('address', $concession->address) }}">
        </div>

        <!-- Code -->
        <div class="form-group">
            <label for="code">KOD POCZTOWY<span class='start-required'>*</span></label>
            <input type="text" class="form-control" id="code" name="code" required value="{{ old('code', $concession->code) }}">
        </div>

        <!-- City -->
        <div class="form-group">
            <label for="city">MIASTO<span class='start-required'>*</span></label>
            <input type="text" class="form-control" id="city" name="city" required value="{{ old('city', $concession->city) }}">
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label for="phone">TELEFON<span class='start-required'>*</span></label>
            <input type="text" class="form-control" id="phone" name="phone" required value="{{ old('phone', $concession->phone) }}">
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email<span class='start-required'>*</span></label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email', $concession->email) }}">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
