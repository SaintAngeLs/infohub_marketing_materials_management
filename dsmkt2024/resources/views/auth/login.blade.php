@extends('layouts.guest')
@section('content')
<div class="items-center justify-center bg-cover bg-no-repeat bg-center login-block text-center">
    <h1>MATERIAŁY REKLAMOWE DS</h1>
    <h2>Logowanie</h2>
    <div class="login-box text-left">
        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

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

        <!-- Form Starts Here -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <x-input-label for="email" :value="__('Login')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Wpisz login"/>
                @error('email')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group mt-4">
                <x-input-label for="password" :value="__('Hasło')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="Wpisz hasło"/>
                @error('password')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Przypomnij hasło') }}
                </a>


            </div>

            {{-- <!-- Remember Me Checkbox -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> --}}

            <!-- Submission Button -->
            <div class="flex items-center justify-center  mt-4">
                {{-- <x-primary-button class="ml-3 login-button ">
                    {{ __('Zalogój się') }}
                </x-primary-button> --}}
                <button type="submit" class="login-button">
                    {{ __('Zalogój się') }}
                </button>
            </div>
        </form>

        <div class="mt-1 text-sm flex">
            <p>Nie posiadasz jeszcze konta?
            <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-600 font-semibold">
                Zarejestruj się
            </a></p>
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
