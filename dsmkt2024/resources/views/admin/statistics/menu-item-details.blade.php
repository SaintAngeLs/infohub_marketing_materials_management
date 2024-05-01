@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <p class="content-tab-name">
        {{ __('Statystyki / Wejścia na zakłądkę ')}}
    </p>

    @include('admin.statistics.partials.statistics-menu')

    <div class="mt-4">
        <h1 class="text-xl font-bold mb-4">Szczegółowe informacje</h1>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Użytkownik</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data i czas</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($userViews as $view)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $view->user->name }} {{ $view->user->surname }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $view->user_ip }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $view->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
