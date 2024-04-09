@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <p class="content-tab-name">{{ __('Statystyki / Pobrania / Pobrania pliku ' . $fileName) }}</p>
    @include('admin.statistics.partials.statistics-menu')
    <div class="container mx-auto">
        <h1 class="text-xl font-bold mb-4">Szczegóły pobrań </h1>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Użytkownik</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data i czas</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($downloads as $download)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $download->name }} {{ $download->surname }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $download->user_ip }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $download->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

