@extends('layouts.app')

@section('content')
    <script>
        function updateNotificationPreference(menuItemId, frequency) {
            $.ajax({
                url: '/user/update-menu-item-notification',
                method: 'POST',
                data: {
                    menu_item_id: menuItemId,
                    frequency: frequency,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Preference updated successfully.');
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating preference:', error);
                }
            });
        }
    </script>
    <div class="container mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <p class="text-lg font-semibold">{{ __('Moje konto / Ustawienia powiadomień o zmianach') }}</p>
                <table class="table-auto w-full mt-4">
                    <thead>
                    <tr>
                        <th>Nazwa zakładki</th>
                        <th>Nigdy</th>
                        <th>Każdego dnia</th>
                        <th>Przy kadej zmianie</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($menuItems as $menuItem)
                        @include('partials.menuItemRow', ['menuItem' => $menuItem, 'level' => 0])
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-4 flex justify-end">
                    <div class="table-button">
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="btn">{{ __('Zapisz zmiany') }}</a>
                    </div>
                    <div class="table-button-2 ml-2">
                        <a href="{{ url()->previous() }}" class="btn">{{ __('Anuluj') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
