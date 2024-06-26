<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'DS - materiały reklamowe') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(app()->environment('production'))
        <!-- Production CSS -->
        <link rel="stylesheet" href="{{ App\Helpers\AssetHelper::asset('resources/css/app.css') }}">
        <!-- Production JS -->
        <script src="{{ App\Helpers\AssetHelper::asset('resources/js/app.js') }}" defer></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('scripts')

    <style type="text/css">
    #banner {
        width: 100%;
        min-width: 960px;
        height: 200px;
        margin: 0 auto;
        background: url('{{ asset("/img/banners/banner_original.jpeg") }}') no-repeat center;
        display: block;
        background-color:#1A1B1B;
        background-size: cover;
        background-position: center calc(80%);
    }

    </style>
</head>
<body>
    <div id="main-wrapper">
        <div id="top-wrapper">
            <div id="top">
                @include('partials.top_content')
            </div>
        </div>
        <div id="banner"></div>
        <div id="progress-bar"></div>
        <div id="content">

                @yield('content')

        </div>
        <div class="push"></div>
    </div>
    @include('partials.footer')
</body>
</html>
