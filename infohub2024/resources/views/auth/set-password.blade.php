@extends('layouts.guest')

@section('content')
<div class="items-center justify-center bg-cover bg-no-repeat bg-center login-block text-center">
    <h1>MATERIAŁY REKLAMOWE DS</h1>
    <h2>Ustawienie hasła</h2>
    <div class="login-box text-left">

        <x-auth-session-status class="mb-2" :status="session('status')" />

        @if ($errors->any())
            <div class="mb-4">
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.update-password', $user->id) }}">
            @csrf


            <div class="form-group mt-4">
                <x-input-label for="password" :value="__('Hasło')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Wpisz hasło"/>
                @error('password')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <x-input-label for="password_confirmation" :value="__('Potwierdź Hasło')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Potwierdź hasło"/>
                @error('password_confirmation')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-center mt-4">
                <button type="submit" class="login-button">
                    {{ __('Wyślij') }}
                </button>
            </div>
        </form>

        <div class="mt-1 text-sm">
            <p class="mt-1">
                <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-600 font-semibold">Zalogój się</a>.
            </p>
            <p>
                Nie masz konta? <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-600 font-semibold">Zarejestruj się</a>.
            </p>
        </div>
    </div>

    <div class="mt-5 text-sm additional-info">
        <p class="mt-4 text-sm">
            Serwis dostępny jest wyłącznie dla osób upoważnionych przez Stellantis Polska Sp. z o.o.<br>
            Gości, którzy nie posiadają loginu i hasła zapraszamy na stronę:<br>
            <a target="_blank" href="https://www.dsautomobiles.pl/" class="text-blue-500 hover:text-blue-600 font-semibold">
                www.dsautomobiles.pl
            </a>
        </p>
    </div>
</div>
@endsection
