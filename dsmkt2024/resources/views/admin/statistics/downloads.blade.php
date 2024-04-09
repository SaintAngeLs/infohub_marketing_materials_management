@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <p class="content-tab-name">{{ __('Statystyki / Pobrania') }}</p>
    @include('admin.statistics.partials.statistics-menu')
    <div class="mt-4">
        <a href="{{ route('menu.statistics.download-excel', ['type' => 'downloads']) }}"" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Pobierz .XLS
        </a>
    </div>
    <h1 class="text-xl font-bold mb-4">Statystyki pobrań</h1>
    <div>
        @include('admin.statistics.partials.filter-form')
    </div>
    <div class="mt-4">
        <h1 class="text-xl font-bold mb-4">Statystyki pobrań ({{ $from }} - {{ $to }})</h1>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nazwa zakładki</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nazwa pliku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liczba pobrań</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Współczynnik proporcji</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($aggregatedDownloads as $download)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $download['menuItem'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('menu.statistics.file-downloads-details', ['fileId' => $download['file_id']]) }}" class="text-blue-600 hover:text-blue-900">{{ $download['file'] }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $download['count'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($download['count'] / $totalDownloads * 100, 2) }}%</td>
                </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</div>
@endsection
