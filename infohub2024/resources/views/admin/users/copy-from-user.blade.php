@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Kopijuj uprawnienia od użytkownika</h2>
    <form id="copy-permissions-form" action="{{ route('menu.permissions.copy.copyUserPermissions') }}" method="POST">
        @csrf
        <input type="hidden" name="target_user_id" value="{{ $targetUserId }}"> <!-- Assuming you have this $targetUserId -->
        <input type="hidden" name="source_user_id" id="source-user-id">
        <div class="list-group">
            @forelse ($users as $user)
                <button type="button" class="list-group-item list-group-item-action user-selection" data-user-id="{{ $user->id }}">
                    {{ $user->name }} {{ $user->surname }}
                </button>
            @empty
                <p class="text-muted">Nie znależiono użytkowników</p>
            @endforelse
        </div>
        <div class="mt-3">
            <button type="submit" id="save-copied-permissions" class="btn btn-primary" disabled>{{ __('Zapisz uprawnienia') }}</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Anuluj</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const userSelectionButtons = document.querySelectorAll('.user-selection');
    userSelectionButtons.forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('source-user-id').value = this.dataset.userId;
            document.getElementById('save-copied-permissions').disabled = false;
        });
    });
});
</script>
@endsection
