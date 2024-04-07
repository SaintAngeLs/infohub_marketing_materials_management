@extends('layouts.app')
@section('content')
    <div class="">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">

                    <p class="content-tab-name">
                        {{ __('Użytkownicy / Edytuj użytkownika') }}
                    </p>

                    @include('components.users-form-component.user-form-component')

                    @php
                        $isEdit = isset($group);
                        $formAction = $isEdit ? route('menu.users.group.update', $group->id) : route('menu.users.group.store');
                    @endphp

                </div>
            </div>
        </div>
    </div>
@endsection
