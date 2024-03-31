@php
    $isEdit = isset($user) && $user->id;
    $formAction = $isEdit ? route('menu.users.update', $user->id) : route('menu.users.store');
    $formMethod = $isEdit ? 'PATCH' : 'POST';
@endphp

<form action="{{ $formAction }}" method="POST">
    @csrf
    @if($isEdit) @method($formMethod) @endif

    <div class="form-group">
        <label for="name">IMIĘ*</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
        @if ($errors->has('name'))
            <div class="invalid-feedback">
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>

     <div class="form-group">
        <label for="surname">NAZWISKO*</label>
        <input type="text" name="surname" id="surname" value="{{ old('surname', $user->surname ?? '') }}" class="form-control {{ $errors->has('surname') ? 'is-invalid' : '' }}">
        @error('surname')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">LOGIN (ADRES EMAIL)*</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" {{ $isEdit ? 'disabled' : '' }}>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="phone">TELEFON*</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="users_groups_id">GRUPA*</label>
        <select name="users_groups_id" id="users_groups_id" class="form-control {{ $errors->has('users_groups_id') ? 'is-invalid' : '' }}" required>
            <option value="">Wybierz</option>
            @foreach($userGroups as $group)
                <option value="{{ $group->id }}" {{ (old('users_groups_id', $user->users_groups_id ?? '') == $group->id) ? 'selected' : '' }}>{{ $group->name }}</option>
            @endforeach
        </select>
        @error('users_groups_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">STATUS UŻYTKOWNIKA*</label>
        <select name="status" id="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
            <option value="active" {{ (old('status') == 'active') ? 'selected' : '' }}>Użytkownik aktywny</option>
            <option value="inactive" {{ (old('status') == 'inactive') ? 'selected' : '' }}>Użytkownik nieaktywny</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if(!$isEdit)
        <div class="form-group">
            <label for="password_option">Ustaw Hasło:</label>
            <select name="password_option" id="password_option" class="form-control" required onchange="togglePasswordFields(this)">
                <option value="no">Nie</option>
                <option value="yes">Tak</option>
            </select>
        </div>

        <div id="password_fields" style="display: none;">
            <div class="form-group">
                <label for="password">HASŁO*</label>
                <input type="password" name="password" id="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">POTWIERDŹ HASŁO*</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    @endif

    <div class="menu-tree-component" id="menu-tree-permissions-user"></div>

    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }}</button>
</form>
