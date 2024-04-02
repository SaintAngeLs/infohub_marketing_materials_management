@extends('layouts.app')

@section('content')
<div class="">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <p class="content-tab-name">{{ __('Statystyki') }}</p>
                <div class="my-4">
                    <a href="{{ route('menu.statistics.entries') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Wejść na zakładkę
                    </a>
                    <a href="{{ route('menu.statistics.downloads') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Pobrań
                    </a>
                    <a href="{{ route('menu.statistics.logins') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Logowań
                    </a>
                </div>
                <div class="mt-4">
                    <form action="{{ route('menu.statistics') }}" method="GET">
                        <label for="from" class="block text-sm font-medium text-gray-700">Od:</label>
                        <input type="date" name="from" id="from" class="p-2 border rounded">

                        <label for="to" class="block text-sm font-medium text-gray-700">Do:</label>
                        <input type="date" name="to" id="to" class="p-2 border rounded">

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Pokaż
                        </button>
                    </form>
                </div>
                <div class="mt-4">
                    <a href="{{ route('menu.statistics.download-excel') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Pobierz .XLS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
