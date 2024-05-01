@php
    $isEdit = isset($concession);
    $formAction = $isEdit ? route('menu.concessions.update', $concession->id) : route('menu.concessions.store');
    $concession = $concession ?? (object)[
        'name' => '',
        'address' => '',
        'code' => '',
        'city' => '',
        'phone' => '',
        'email' => '',
    ];
@endphp
<div id="concessions-component">
    <form action="{{ $formAction }}" method="POST" >
        @csrf
        @if($isEdit) @method('PATCH') @endif

        <div class="concession-component-add">
            <div class="form-group">
                <label for="name">NAZWA<span class='start-required'>*</span></label>
                <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $concession->name) }}">
                @if ($errors->has('name'))
                    <div class="name-error">{{ $errors->first('name') }}</div>
                @endif
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="address">ADRES<span class='start-required'>*</span></label>
                <input type="text" class="form-control" id="address" name="address" required value="{{ old('address', $concession->address) }}">
                @if ($errors->has('address'))
                    <div class="address-error">{{ $errors->first('address') }}</div>
                @endif
                <div class=invalid-feedback></div>
            </div>

            <div class="form-group">
                <label for="code">KOD POCZTOWY<span class='start-required'>*</span></label>
                <input type="text" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" id="code" name="code" required value="{{ old('code', $concession->code) }}">
                @if ($errors->has('code'))
                    <div class="code-error">{{ $errors->first('code') }}</div>
                @endif
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="city">MIASTO<span class='start-required'>*</span></label>
                <input type="text" class="form-control" id="city" name="city" required value="{{ old('city', $concession->city) }}">
                @if ($errors->has('city'))
                    <div class="city-error">{{ $errors->first('city') }}</div>
                @endif
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="phone">TELEFON<span class='start-required'>*</span></label>
                <input type="text" class="form-control" id="phone" name="phone" required value="{{ old('phone', $concession->phone) }}">
                @if ($errors->has('phone'))
                    <div class="phone-error">{{ $errors->first('phone') }}</div>
                @endif
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="email">Email<span class='start-required'>*</span></label>
                <input type="email" class="form-control" id="email" name="email" required value="{{ old('email', $concession->email) }}">
                @if ($errors->has('email'))
                    <div class="email-error">{{ $errors->first('email') }}</div>
                @endif
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
