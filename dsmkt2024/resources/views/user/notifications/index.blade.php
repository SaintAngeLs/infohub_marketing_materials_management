@extends('layouts.app')
@section('content')
    <div class="">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">

                    <p class="content-tab-name">
                        {{ __('Moje konto / Powiadomienie e-mail o zmianach') }}
                    </p>


                    <input type="hidden" id="user-id" value="{{ $user->id }}">


                    <div id="menu-tree-notifications"></div>

                    <div class="mt-4">
                        <button type="button" id="save-permissions" class="btn btn-primary">{{ __('Zapisz zmiany') }}</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Anuluj') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
