@php
    $isEdit = isset($user) && $user->id;
    $formAction = $isEdit ? route('menu.users.update', $user->id) : route('menu.users.store');
    $formMethod = $isEdit ? 'PATCH' : 'POST';
@endphp

<form action="{{ $formAction }}" method="POST">
    @csrf
    @if($isEdit) @method($formMethod) @endif

    <!-- First Name -->
    <div class="form-group">
        <label for="name">IMIĘ*</label>
        <input type="text" name="name" id="name" required value="{{ old('name', $user->name ?? '') }}" class="form-control">
    </div>

    <!-- Last Name -->
    <div class="form-group">
        <label for="surname">NAZWISKO*</label>
        <input type="text" name="surname" id="surname" required value="{{ old('surname', $user->surname ?? '') }}" class="form-control">
    </div>

    <!-- Email (Login) -->
    <div class="form-group">
        <label for="email">LOGIN (ADRES EMAIL)*</label>
        <input type="email" name="email" id="email" required value="{{ old('email', $user->email ?? '') }}" class="form-control" {{ $isEdit ? 'disabled' : '' }}>
    </div>

    <!-- Phone -->
    <div class="form-group">
        <label for="phone">TELEFON*</label>
        <input type="text" name="phone" id="phone" required value="{{ old('phone', $user->phone ?? '') }}" class="form-control">
    </div>

    <!-- User Group -->
    <div class="form-group">
        <label for="users_groups_id">GRUPA*</label>
        <select name="users_groups_id" id="users_groups_id" class="form-control" required>
            <option value="">Wybierz</option>
            @foreach($userGroups as $group)
                <option value="{{ $group->id }}" {{ (old('users_groups_id', $user->users_groups_id ?? '') == $group->id) ? 'selected' : '' }}>{{ $group->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Password -->
    @if(!$isEdit)
    <div class="form-group">
        <label for="password">HASŁO*</label>
        <input type="password" name="password" id="password" required class="form-control">
    </div>

    <!-- Confirm Password -->
    <div class="form-group">
        <label for="password_confirmation">POTWIERDŹ HASŁO*</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
    </div>
    @endif

    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }}</button>
</form>
