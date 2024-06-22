<tr class="pl-{{ $level * 4 }}">
    <td style="padding-left: {{ $level * 20 }}px;">{{ $menuItem->name }}</td>
    @php
        $notificationPreference = $menuItem->notificationPreferences->first();
        $neverChecked = $notificationPreference && $notificationPreference->frequency === 0 ? 'checked' : '';
        $dailyChecked = $notificationPreference && $notificationPreference->frequency === 1 ? 'checked' : '';
        $onChangeChecked = $notificationPreference && $notificationPreference->frequency === 2 ? 'checked' : '';
    @endphp
    <td><input type="radio" onclick="updateNotificationPreference({{ $menuItem->id }}, 0);" name="notification_preference_{{ $menuItem->id }}" value="0" {{ $neverChecked }}></td>
    <td><input type="radio" onclick="updateNotificationPreference({{ $menuItem->id }}, 1);" name="notification_preference_{{ $menuItem->id }}" value="1" {{ $dailyChecked }}></td>
    <td><input type="radio" onclick="updateNotificationPreference({{ $menuItem->id }}, 2);" name="notification_preference_{{ $menuItem->id }}" value="2" {{ $onChangeChecked }}></td>
</tr>
@if($menuItem->children->isNotEmpty())
    @foreach($menuItem->children as $child)
        @include('partials.menuItemRow', ['menuItem' => $child, 'level' => $level + 1])
    @endforeach
@endif
