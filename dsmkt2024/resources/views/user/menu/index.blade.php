@extends('layouts.app')
@section('content')
    <div class="">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">

                    <p class="content-tab-name">
                        {{ __('Menu') }}
                    </p>
                    @forelse($menuItems as $menuItem)
                    <h3>Files for {{ $menuItem->name }}</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Typ</th>
                                <th>Rozmiar</th>
                                <th>Aktualizacja</th>
                                <th>Pobierz</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menuItem->files as $file)
                                <tr>
                                    <td>{{ $file->name }}</td>
                                    <td>{{ $file->extension }}</td>
                                    <td>{{  \App\Helpers\FormatBytes::formatBytes($file->weight) }}</td>
                                    <td>{{ $file->updated_at->format('d.m.Y H:i:s') }}</td>
                                    <td><a href="{{ route('files.download', $file->id) }}">Pobierz</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @empty
                    <li>Brak plików</li>
                @endforelse

                    {{-- <div class="menu-tree-component" id="menu-tree"></div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
