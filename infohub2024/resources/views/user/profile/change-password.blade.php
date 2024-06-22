@extends('layouts.app')

@section('content')

<div class="">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <p class="content-tab-name">
                    <a href="{{ route('user.my-account') }}" class="text-gray-900 hover:underline">
                        {{ __('Moje konto') }}
                    </a>
                    /
                    {{ __('Zmiana hasła') }}
                </p>

                <form method="POST" action="{{ route('user.change-password.post') }}">
                    @csrf
                    <div class="form-group">
                        <label for="current_password">Obecne hasło</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nowe hasło</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Potwirdzenie nowego hasła</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <div class="table-button">
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="btn">{{ __('Zmień hasło') }}</a>
                        </div>
                        <div class="table-button-2 ml-2">
                            <a href="{{ route('user.my-account') }}" class="btn">{{ __('Anuluj') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">

</div>
@endsection
