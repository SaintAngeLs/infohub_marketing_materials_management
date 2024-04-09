@extends('layouts.guest')

@section('content')
<div class="items-center justify-center bg-cover bg-no-repeat bg-center login-block text-center">
    <h2>MATERIAŁY REKLAMOWE DS</h2>
    <h4>Zgłoszenie było pomyślnie wysłane do rozważania</h4>
    <div class="mt-5">
        <p>Masz już konto?
        <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-600 font-semibold">Zaloguj się</a></p>
    </div>
    <div class="mt-5 text-sm additional-info">
        <p class="mt-4">
            Serwis dostępny jest wyłącznie dla osób upoważnionych przez Stellantis Polska Sp. z o.o.<br>
            Gości, którzy nie posiadają loginu i hasła zapraszamy na stronę:<br>
            <a target="_blank" href="https://www.dsautomobiles.pl/" class="text-blue-500 hover:text-blue-600 font-semibold">
                www.dsautomobiles.pl
            </a>
        </p>
    </div>
</div>
@endsection
