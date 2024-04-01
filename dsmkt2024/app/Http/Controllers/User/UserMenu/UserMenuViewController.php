<?php

namespace App\Http\Controllers\User\UserMenu;

use Illuminate\Http\Request;
use App\Models\MenuItems\MenuItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserMenuViewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $menuItems = $user->accessibleMenuItems()->get();
        // Optionally, preload the first menu item's files or content if you want to display something initially
        $firstMenuItemId = $menuItems->first()->id ?? null;
        return redirect()->route('menu.files', ['menuItemId' => $firstMenuItemId]);
    }

    public function showFilesForMenuItem($menuItemId) {
        $user = Auth::user();
        $menuItems = $user->accessibleMenuItems()->get();
        $selectedMenuItem = MenuItem::with('files')->findOrFail($menuItemId);
        return view('user.menu.index', compact('menuItems', 'selectedMenuItem'));
    }


    // You might want to create a method in User model to fetch accessible menu items
    // Example method inside User model:
    // public function accessibleMenuItems() {
    //     // Fetch menu items based on user permissions
    //     return MenuItem::where('condition', 'your_logic_here')->get();
    // }
}
