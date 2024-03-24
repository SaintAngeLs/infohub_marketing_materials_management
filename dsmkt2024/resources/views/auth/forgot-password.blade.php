@extends('layouts.guest')

@section('content')
<div class="items-center justify-center bg-cover bg-no-repeat bg-center login-block text-center">
    <h1>MATERIAŁY REKLAMOWE DS</h1>
    <h2>Przypomnienie hasła</h2>
    <h2>Wyślemy Ci link resetujący, jeżeli konto istnieje</h2>
    <div class="login-box text-left">
        <!-- Session Status -->
        <x-auth-session-status class="mb-2" :status="session('status')" />

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="mb-4">
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <x-input-label for="email" :value="__('Adres Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="Wpisz adres Email" />
                @error('email')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
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



{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
