@extends('layouts.app')
@section('content')
<div class="">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <p class="content-tab-name">{{ __('Samochody') }}</p>

                {{-- <p class="table-button">
                    <a href="{{ route('menu.users.applications.create') }}" class="btn">Dodaj nowe zgloszenie</a>
                </p> --}}

                @include('components.autos-component.autos-form-component')

            </div>
        </div>
    </div>
</div>
@endsection
