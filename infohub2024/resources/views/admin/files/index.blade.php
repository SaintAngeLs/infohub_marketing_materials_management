@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="row p-6 text-gray-900 dark:text-gray-900">
                    <div class="col-12">
                        <p class="content-tab-name">{{ __('Pliki') }}</p>
                    </div>
                </div>
                <div class="row p-6 text-gray-900 dark:text-gray-900">
                    <div class="col-12">
                        <p class="table-button">
                            <a href="{{ route('menu.files.create') }}" class="btn">Dodaj nowy plik</a>
                        </p>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="py-4">Nazwa</th>
                                <th class="py-4">Status</th>
                                <th class="py-4">Właściciele</th>
                                <th class="py-4">Widoczność</th>
                                <th class="py-4">Pliki</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($formattedMenuItems as $menuItem)
                                @include('partials.menu_item_file', ['menuItem' => $menuItem, 'depth' => 0])
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
