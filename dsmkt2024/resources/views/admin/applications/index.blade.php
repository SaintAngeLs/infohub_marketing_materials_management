@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <h1 class="text-2xl font-semibold mb-4">{{ __('Zgłoszenia') }}</h1>

                {{-- <div class="mb-4">
                    <a href="{{ route('menu.users.applications.create') }}" class="btn btn-primary">Dodaj nowe zgloszenie</a>
                </div> --}}

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nazwa firmy</th>
                            <th scope="col" class="px-6 py-3">Imię Nazwisko / Nazwa koncesji</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Telefon</th>
                            <th scope="col" class="px-6 py-3">Data zgłoszenia</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3" colspan="3">Akcje</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($applications as $application)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-6 py-4">{{ $application->company_name }}</td>
                                <td class="px-6 py-4">{{ $application->name }} {{ $application->surname }}</td>
                                <td class="px-6 py-4">{{ $application->email }}</td>
                                <td class="px-6 py-4">{{ $application->phone }}</td>
                                <td class="px-6 py-4">{{ $application->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="px-6 py-4">
                                    @if($application->status == 0)
                                        <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Oczekuje</span>
                                    @elseif($application->status == 1)
                                        <span class="bg-green-200 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Zaakceptowano</span>
                                    @else
                                        <span class="bg-red-200 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Odrzucono</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="table-button">
                                        <a href="{{ route('menu.users.applications.accept', $application->id) }}" class="btn ">Akceptuj</a>
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="table-button">
                                        <a href="{{ route('menu.users.applications.reject', $application->id) }}" class="btn ">Odrzuć</a>
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="table-button">
                                        <a href="{{ route('menu.users.applications.details', $application->id) }}" class="btn ">Szczegóły</a>
                                    </p>
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
