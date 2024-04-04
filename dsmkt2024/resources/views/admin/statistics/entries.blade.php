@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <p class="content-tab-name">{{ __('Statystyki / Wejścia na zakładkę') }}</p>
    @include('admin.statistics.partials.statistics-menu')
    <div class="mt-4">
        <a href="{{ route('menu.statistics.download-excel', ['type' => 'entries']) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Pobierz .XLS
        </a>
    </div>
    <h1 class="text-xl font-bold mb-4">Menu Item View Statistics</h1>
    <div>
        @include('admin.statistics.partials.filter-form')
    </div>

    <div class="mt-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nazwa Zakładki</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liczba Przeglądów</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proporcja estymowana</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($menuViewCounts as $viewCount)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $viewCount->menuItem->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $viewCount->views }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format(($viewCount->views / $totalViews) * 100, 2) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
