@extends('layouts.app')

@section('content')
<div class="">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <p class="content-tab-name">{{ __('Samochody') }}</p>

                <p class="table-button">
                    <a href="{{ route('menu.autos.create') }}" class="btn">Dodaj samochód</a>
                </p>

                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nazwa</th>
                            <th class="px-4 py-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($autos as $auto)
                            <tr>
                                <td class="border px-4 py-2">{{ $auto->name }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('menu.autos.edit', $auto->id) }}" class="btn">Edytuj</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="border px-4 py-2 text-center">Brak samochodów</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
