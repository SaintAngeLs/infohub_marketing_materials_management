@extends('layouts.guest')
@section('content')
<div class="w-full items-center justify-center bg-cover bg-no-repeat bg-center login-block text-center">
    <h2>MATERIAŁY REKLAMOWE DS</h2>
    <h4>Rejestracja</h4>
    <div class="login-box text-left">
        @if ($errors->any())
            <div class="mb-4">
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('access-request.store') }}" id="accessRequestForm">
            @csrf

            <div class="form-group">
                <label for="company_name" class="block font-medium text-sm text-gray-700">Nazwa firmy</label>
                <input id="company_name" class="block mt-1 w-full" type="text" name="company_name" required autofocus placeholder="Wpisz nazwę firmy"/>
                @error('company_name')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <label for="first_name" class="block font-medium text-sm text-gray-700">Imię</label>
                <input id="first_name" class="block mt-1 w-full" type="text" name="first_name" required placeholder="Wpisz imię"/>
                @error('first_name')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <label for="last_name" class="block font-medium text-sm text-gray-700">Nazwisko</label>
                <input id="last_name" class="block mt-1 w-full" type="text" name="last_name" required placeholder="Wpisz nazwisko"/>
                @error('last_name')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                <input id="email" class="block mt-1 w-full" type="email" name="email" required placeholder="Wpisz email"/>
                @error('email')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <label for="phone" class="block font-medium text-sm text-gray-700">Telefon</label>
                <input id="phone" class="block mt-1 w-full" type="text" name="phone" required placeholder="Wpisz telefon"/>
                @error('phone')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-center mt-4">
                <button type="submit" class="login-button">
                    Zarejestruj się
                </button>
            </div>
        </form>

        <div class="mt-1 text-sm flex">
            <p>Masz już konto?
            <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-600 font-semibold">
                Zaloguj się
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
