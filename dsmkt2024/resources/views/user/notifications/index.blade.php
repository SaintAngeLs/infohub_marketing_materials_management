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

                <p class="text-lg font-semibold">{{ __('My Account / Email Notification Changes') }}</p>

                <input type="hidden" id="user-id" value="{{ $user->id }}">

                <!-- Notification settings table -->
                <table class="w-full mt-4 table-auto">
                    @foreach($menuItems as $menuItem)
                        @include('partials.menuItemRow', ['menuItem' => $menuItem, 'level' => 0])
                    @endforeach
                </table>

                <div class="mt-4 flex justify-end">
                    <button class="btn btn-primary">{{ __('Save Changes') }}</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary ml-2">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
