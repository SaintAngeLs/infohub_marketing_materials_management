@extends('layouts.app')
@section('content')
    <div class="">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">

                    <p class="content-tab-name">
                        {{ __('Grupy') }}
                    <p class="content-tab-name">

                    <p  class="table-button">
                        <a href="{{ route('menu.users.group.create') }}" class="btn">Dodaj nową grupę</a>
                    </p>

                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imię</th>
                                <th>Liczba Użytkownika</th>
                                <th>Liczba menu</th>
                                {{-- <th>Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userGroups as $group)
                            <tr>
                                <td>{{ $group->id }}</td>
                                <td>
                                    <a href="{{ route('menu.users.group.edit', $group->id) }}">
                                    {{ $group->name }}
                                </td>
                                </a>
                                <td>{{ $group->users->count() }}</td>
                                <td>{{ $group->menuItems->count() }}</td>
                                {{-- <td>
                                    <a href="{{ route('admin.groups.edit', $group->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
