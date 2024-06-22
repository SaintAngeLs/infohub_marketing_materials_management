@extends('layouts.app')

@section('content')
<div class="">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <p class="content-tab-name">{{ __('Moje konto') }}</p>

                <div class="flex space-x-4">
                    <div class="table-button mt-4">
                        <a href="{{ route('user.notifications') }}" class="btn">Powiadomienia e-mail o zmianach</a>
                    </div>

                    <div class="table-button-2 mt-4">
                        <a href="{{ route('user.change-password') }}" class="btn">Zmiana hasła</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
