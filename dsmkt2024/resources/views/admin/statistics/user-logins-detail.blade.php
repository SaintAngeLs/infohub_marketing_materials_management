@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    {{-- Ensure $user is being passed to this view by the controller --}}
    <p class="content-tab-name">
        {{ __('Statystyki / Logowania użytkownika / ') . $user->name . ' ' . $user->surname }}
    </p>

    @include('admin.statistics.partials.statistics-menu')
    <div class="mt-4">
        <h1 class="text-xl font-bold mb-4">Szczegółowe logowania użytkownika</h1>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Użytkownik</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data i czas</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($userLogins as $login)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $login->user->name }} {{ $login->user->surname }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $login->ip }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $login->fingerprint->format('d.m.Y H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
