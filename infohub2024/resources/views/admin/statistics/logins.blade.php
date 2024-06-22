{{-- resources/views/admin/statistics/logins.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <p class="content-tab-name">{{ __('Statystyki / Logowania') }}</p>
    @include('admin.statistics.partials.statistics-menu')
    <div class="mt-4">
        <a href="{{ route('menu.statistics.download-excel', ['type' => 'logins']) }}"" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Pobierz .XLS
        </a>
    </div>
    <h1 class="text-xl font-bold mb-4">Statystyki logowań</h1>
    <div>
        @include('admin.statistics.partials.filter-form')
    </div>
    <div class="mt-4">
        <h1 class="text-xl font-bold mb-4">Statystyki logowań ({{ $from }} - {{ $to }})</h1>
        <div>Total Logins: {{ $totalLogins }}</div>
        <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imię / Nazwisko</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grupa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liczba logowań</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Współczynnik proporcji</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($logins as $login)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if (!empty($login['user_id']))
                            <a href="{{ route('menu.statistics.user.logins.details', ['userId' => $login['user_id']]) }}">
                                {{ $login['name'] }} {{ $login['surname'] }}
                            </a>
                        @else
                            {{ $login['name'] }} {{ $login['surname'] }} (User ID missing)
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $login['user_group'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $login['login_count'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($login['login_count'] / $totalLogins * 100, 2) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
