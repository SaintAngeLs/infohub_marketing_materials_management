@extends('layouts.app')

@section('content')
<div class="">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">

                <p class="content-tab-name">
                    {{ __('Użytkownicy') }}
                </p>

                <p class="table-button">
                    <a href="{{ route('menu.users.create') }}" class="btn">Dodaj użytkownika</a>
                </p>

                <table class="w-full">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route('menu.users', ['sort' => 'name', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                                    Nazwisko Imię
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('menu.users', ['sort' => 'group', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc']) }}">
                                    Grupa
                                </a>
                            </th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('menu.users.edit', $user->id) }}">
                                    {{ $user->surname }} {{ $user->name }}
                                </a>
                            </td>
                            <td>{{ $user->usersGroup->name ?? 'Brak' }}</td>
                            <td>{{ $user->active ? 'Aktywny' : 'Nieaktywny' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
