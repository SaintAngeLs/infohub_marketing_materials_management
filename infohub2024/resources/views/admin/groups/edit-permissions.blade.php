@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">
                    <p class="content-tab-name">
                        {{ __('Grupy / Edytuj grupę / Edytuj uprawnienia') }}
                    </p>

                    @if($groupId)
                        <input type="hidden" id="group-id" value="{{ $groupId }}">
                    @endif

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>Uprawnienia</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($formattedMenuItems as $menuItem)
                            @include('partials.menu_item_permission', ['menuItem' => $menuItem, 'depth' => 0])
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <button type="button" id="save-permissions" class="btn btn-primary">{{ __('Zapisz uprawnienia') }}</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Anuluj') }}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>

    </script>
@endsection
