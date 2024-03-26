@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Dodanie nowej zakładki menu') }}
                    </h2>
                    @include('components.menu-form-component.menu-form-component', ['menuItemsToSelect' => $menuItemsToSelect])
                </div>
            </div>
        </div>
    </div>
@endsection