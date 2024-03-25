@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">

                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-900 leading-tight">
                        {{ __('Pliki') }}
                    </h2>
                    <p  class="table-button">
                        <a href="{{ route('menu.create') }}" class="btn">Dodaj nowy plik</a>
                    </p>

                    <div id="menu-tree-files"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
