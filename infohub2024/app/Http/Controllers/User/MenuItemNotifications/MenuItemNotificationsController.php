<?php

namespace App\Http\Controllers\User\MenuItemNotifications;

use App\Helpers\FormatBytes;
use App\Http\Controllers\Controller;
use App\Models\ExtendedUser;
use App\Models\GroupPermission;
use App\Models\MenuItems\MenuItem;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MenuItemNotificationsController extends Controller
{
    public function getMenuItemsNotifications(Request $request)
    {
        $userId = $request->input('user_id', Auth::id());
        $menuItems = MenuItem::with(['children'])->get();

        $formattedMenuItems = $this->formatForJsTreeUserNotifications($menuItems, $userId);

        return response()->json($formattedMenuItems);
    }

    public function formatForJsTreeUserNotifications($menuItems, $userId = null)
    {
        $formatted = [];
        foreach ($menuItems as $item) {
            $preference = $this->getUserNotificationPreference($item->id, $userId);
            $checkedNever = $preference === 0 ? "checked='checked'" : "";
            $checkedDaily = $preference === 1 ? "checked='checked'" : "";
            $checkedOnChange = $preference === 2 ? "checked='checked'" : "";

            $checkboxesHtml = <<<HTML
                <div class='notification-preferences'>
                    <input type='radio' onclick="updateNotificationPreference({$item->id}, 0);" name='notification_preferences[{$item->id}]' value='0' {$checkedNever}> Nigdy
                    <input type='radio' onclick="updateNotificationPreference({$item->id}, 1);" name='notification_preferences[{$item->id}]' value='1' {$checkedDaily}> Raz dziennie
                    <input type='radio' onclick="updateNotificationPreference({$item->id}, 2);" name='notification_preferences[{$item->id}]' value='2' {$checkedOnChange}> Przy każdej zmianie
                </div>
            HTML;

            $nodeContent = <<<HTML
                <div class='js-tree-node-content' data-node-id="{$item->id}">
                    <span class='node-name'>{$item->name}</span>
                    <span class=''>$checkbo xesHtml</span>
                </div>
            HTML;

            $formattedItem = [
                'id' => $item->id,
                'text' => $nodeContent,
                'children' => $item->children->isEmpty() ? [] : $this->formatForJsTreeUserNotifications($item->children, $userId),
            ];

            $formatted[] = $formattedItem;
        }
        return $formatted;
    }
    public function updateNotificationPreference(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|integer',
            'frequency' => 'required|integer|min:0|max:2',
        ]);

        $userId = Auth::id();
        $menuItemId = $request->menu_item_id;
        $frequency = $request->frequency;

        $notification = UserNotification::updateOrCreate(
            [
                'user_id' => $userId,
                'menu_item_id' => $menuItemId,
            ],
            ['frequency' => $frequency]
        );
        \Log::info('The notification frequesny has been sent', $notification->toArray());

        if ($notification) {
            return response()->json(['success' => true, 'message' => 'Notification preference updated.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update notification preference.'], 500);
        }
    }

    private function getUserNotificationPreference($menuItemId, $userId)
    {
        $preference = UserNotification::where('user_id', $userId)
                                    ->where('menu_item_id', $menuItemId)
                                    ->first();

        \Log::debug('User Notification Preference', [
            'userId' => $userId,
            'menuItemId' => $menuItemId,
            'preference' => $preference
        ]);

        return $preference ? $preference->frequency : null;
    }
}
