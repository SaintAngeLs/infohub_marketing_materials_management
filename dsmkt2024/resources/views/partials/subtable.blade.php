<table class="ml-{{ $level * 4 }} table-auto">
    @foreach($subItems as $menuItem)
        <tr class="{{ $level > 0 ? 'pl-' . ($level * 4) : '' }}">
            <td>{{ $menuItem->name }}</td>
            @php
                $notificationPreference = $menuItem->notificationPreferences->first();
                $neverChecked = $notificationPreference && $notificationPreference->frequency === 0 ? 'checked' : '';
                $dailyChecked = $notificationPreference && $notificationPreference->frequency === 1 ? 'checked' : '';
                $onChangeChecked = $notificationPreference && $notificationPreference->frequency === 2 ? 'checked' : '';
            @endphp
            <td>
                <input type="radio" onclick="updateNotificationPreference({{ $menuItem->id }}, 0);" name="notification_preference_{{ $menuItem->id }}" value="0" {{ $neverChecked }}> Never
            </td>
            <td>
                <input type="radio" onclick="updateNotificationPreference({{ $menuItem->id }}, 1);" name="notification_preference_{{ $menuItem->id }}" value="1" {{ $dailyChecked }}> Daily
            </td>
            <td>
                <input type="radio" onclick="updateNotificationPreference({{ $menuItem->id }}, 2);" name="notification_preference_{{ $menuItem->id }}" value="2" {{ $onChangeChecked }}> On Change
            </td>
        </tr>
        @if($menuItem->children->isNotEmpty())
            @include('partials.subtable', ['subItems' => $menuItem->children, 'level' => $level + 1])
        @endif
    @endforeach
</table>
