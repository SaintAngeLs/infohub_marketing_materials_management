@extends('layouts.guest')

@section('content')
    <div class="w-full flex items-center justify-center bg-cover bg-no-repeat bg-center login-block text-center">
        <div class="login-container">
            <h2>MATERIAŁY REKLAMOWE DS</h2>
            <h4>Dziękujemy</h4>
            <div class="login-box text-left">
                <p class="mt-4 text-sm text-green-600">
                    Wysłaliśmy email z linkiem do resetowania hasła. Dziękujemy.
                </p>
                <p class="mt-4 text-sm">
                    <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-600 font-semibold">
                        Powrót do strony logowania
                    </a>
                </p>
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
    </div>
@endsection
