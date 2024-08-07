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
        <link rel="stylesheet" href="{{ App\Helpers\AssetHelper::asset('resources/css/app.css') }}">
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

        #custom-progress-bar {
            position: relative;
            height: 4px;
            background: #ccc;
        }

    </style>

    <style>
        .search-col {
            display: flex;
            justify-content: flex-end;
            padding: 20px 0;
        }

        .search-box {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            background-color: #fff;
        }

        .search-box input[type="text"] {
            border: none;
            padding: 10px;
            flex: 1;
            outline: none;
        }

        .search-box input[type="submit"] {
            border: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-box input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>


    <script src="https://unpkg.com/nprogress/nprogress.js"></script>
    <script>
        NProgress.configure({
            showSpinner: false,
            trickleSpeed: 200,
            easing: 'ease',
            parent: '#custom-progress-bar',
            speed: 500
        });
    </script>
</head>
<body onload="NProgress.done();">
<script>
    NProgress.start();
</script>

<script>
    function searchMe() {
        var query = document.getElementById('search').value;
        if(query.length < 2) {
            alert('Please enter at least 2 characters');
            return false;
        }
        window.location.href = `/search?query=${encodeURIComponent(query)}`;
        return false;
    }
</script>
<div id="main-wrapper">
    <div id="top-wrapper">
        <div id="top">
            @include('partials.top_content')
        </div>
    </div>
    <div id="banner"></div>
    <div id="custom-progress-bar"></div>
    <div id="content-wrapper">
        <div id="content">
            <div class="clearfix"></div>
            <div class="left-col">
                @php
                    $isAdminPanel = Auth::check() && Auth::user()->isAdmin() && (Str::startsWith(Route::currentRouteName(), 'menu.') || request()->routeIs('menu')  || session('isAdminPanel'));
                @endphp

                @if($isAdminPanel)
                    @include('partials.admin_menu')
                @else
                    @include('partials.user_menu')
                @endif
            </div>

            @auth
                <div class="search-col">
                    <form id="searchbox" action="{{ route('search') }}" method="GET" class="search-box">
                        <input id="search" name="query" type="text" placeholder="szukaj" value="">
                        <input class="submit" type="submit" value="Szukaj">
                    </form>
                </div>
            @endauth

            <div class="right-col">
                @yield('content')
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="push"></div>
</div>
@include('partials.footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('a.ajax-link').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#content').load(url + ' #content > *');
            history.pushState(null, '', url);
        });

        $(window).on('popstate', function() {
            location.reload();
        });
    });
</script>
</body>
</html>
