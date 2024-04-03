{{-- resources/views/admin/statistics/logins.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-xl font-bold mb-4">Login Statistics</h1>
    <div>
        {{-- Filter Form --}}
        @include('admin.statistics.partials.filter-form')
    </div>
    <div class="mt-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($logins as $login)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $login->fingerprint }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $login->ip }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $login->user->name ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
