@extends('layouts.app')
@section('content')
    <div class="">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">

                    <p class="content-tab-name">
                        {{ __('Użytkownicy / Edytuj użytkownika / Edytuj uprawnienia') }}
                    </p>

                    @if($isEdit)
                        <input type="hidden" id="user-id" value="{{ $user->id }}">
                    @endif

                    <div class="mt-4 flex justify-between">
                        <a href="{{ route('menu.permissions.copy.from.user', ['targetUserId' => $user->id ?? null]) }}" class="btn btn-secondary mx-2">{{ __('Kopijuj uprawnienia od użytkownika') }}</a>
                    </div>


                    <div class="menu-tree-component" id="menu-tree-permissions-user"></div>

                    <div class="mt-4">
                        <button type="button" id="save-permissions" class="btn btn-primary">{{ __('Zapisz uprawnienia') }}</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Anuluj') }}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
