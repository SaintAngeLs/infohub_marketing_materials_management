@extends('layouts.app')
@section('content')
    <div class="">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">
                    <p class="content-tab-name">
                        {{ __('Zgłoszenia') }}
                    </p class="content-tab-name">

                    <p  class="table-button">
                        <a href="{{ route('menu.users.applications.create') }}" class="btn">Dodaj nowe zgloszenie</a>
                    </p>

                    <!-- Concessions Table -->
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nazwa firmy	</th>
                                <th scope="col" class="px-6 py-3">Imię Nazwisko / Nazwa koncesji</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Telefon</th>
                                <th scope="col" class="px-6 py-3">Data zgłoszenia</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
