@extends('layouts.app')
@section('content')
    <div class="">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">

                    <p class="content-tab-name">
                        {{ __('Grupy / Dodaj nową grupę') }}
                    <p class="content-tab-name">

                    {{-- <p  class="table-button">
                        <a href="{{ route('menu.users.group.create') }}" class="btn">Dodaj nową grupę</a>
                    </p> --}}

                    @include('components.groups-component.group-form-component', ['group' => $userGroup]);
                </div>
            </div>
        </div>
    </div>
@endsection
