@extends('layouts.app')
@section('content')
<div class="">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <p class="content-tab-name">{{ __('Zgłoszenia') }}</p>

                {{-- <p class="table-button">
                    <a href="{{ route('menu.users.applications.create') }}" class="btn">Dodaj nowe zgloszenie</a>
                </p> --}}

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nazwa firmy</th>
                            <th scope="col" class="px-6 py-3">Imię Nazwisko / Nazwa koncesji</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Telefon</th>
                            <th scope="col" class="px-6 py-3">Data zgłoszenia</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td class="px-6 py-4">{{ $application->company_name }}</td>
                            <td class="px-6 py-4">{{ $application->name }} {{ $application->surname }}</td>
                            <td class="px-6 py-4">{{ $application->email }}</td>
                            <td class="px-6 py-4">{{ $application->phone }}</td>
                            <td class="px-6 py-4">{{ $application->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-6 py-4">
                                @if($application->status == 0) Oczekuje
                                @elseif($application->status == 1) Zaakceptowano
                                @else Odrzucono
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('menu.users.applications.accept', $application->id) }}" class="btn btn-success">Accept</a>
                                <a href="{{ route('menu.users.applications.reject', $application->id) }}" class="btn btn-danger">Reject</a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('menu.users.applications.details', $application->id) }}" class="btn btn-info">Szczegóły</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
