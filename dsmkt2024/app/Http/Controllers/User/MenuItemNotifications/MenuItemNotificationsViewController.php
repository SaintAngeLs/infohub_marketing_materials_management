<?php

namespace App\Http\Controllers\User\MenuItemNotifications;

use App\Helpers\FormatBytes;
use App\Http\Controllers\Controller;
use App\Models\ExtendedUser;
use App\Models\GroupPermission;
use App\Models\MenuItems\MenuItem;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MenuItemNotificationsViewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $menuItems = MenuItem::with(['children', 'notificationPreferences' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get()->toTree();

        return view('user.notifications.index', compact('user', 'menuItems'));
    }
}
