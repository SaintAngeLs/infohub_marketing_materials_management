@extends('layouts.app')
@section('content')
    <div class="">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">
                    <p class="content-tab-name">
                        {{ __('Koncesje') }}
                    </p class="content-tab-name">

                    <p  class="table-button">
                        <a href="{{ route('menu.concessions.create') }}" class="btn">Dodaj nową koncesję</a>
                    </p>

                    <!-- Concessions Table -->
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nazwa</th>
                                <th scope="col" class="px-6 py-3">Adres</th>
                                <th scope="col" class="px-6 py-3">Kod</th>
                                <th scope="col" class="px-6 py-3">Miasto</th>
                                <th scope="col" class="px-6 py-3">Telefon</th>
                                <th scope="col" class="px-6 py-3">E-mail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($concessions as $concession)
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <td class="px-6 py-4">
                                    <a href="{{ route('menu.concessions.edit', $concession->id) }}">{{ $concession->name }}</a>
                                </td>
                                <td class="px-6 py-4">{{ $concession->address }}</td>
                                <td class="px-6 py-4">{{ $concession->code }}</td>
                                <td class="px-6 py-4">{{ $concession->city }}</td>
                                <td class="px-6 py-4">{{ $concession->phone }}</td>
                                <td class="px-6 py-4">{{ $concession->email }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
