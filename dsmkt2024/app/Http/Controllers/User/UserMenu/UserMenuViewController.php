<?php

namespace App\Http\Controllers\User\UserMenu;

use App\Contracts\IStatistics;
use Illuminate\Http\Request;
use App\Models\MenuItems\MenuItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserMenuViewController extends Controller
{
    protected $statisticsService;

    public function __construct(IStatistics $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }
    public function index()
    {
        $user = Auth::user();
        $menuItems = $user->accessibleMenuItems()->get();
        $firstMenuItemId = $menuItems->first()->id ?? null;
        return redirect()->route('menu.files', ['menuItemId' => $firstMenuItemId]);
    }

    public function showFilesForMenuItem($menuItemId) {
        $user = Auth::user();
        $menuItems = $user->accessibleMenuItems()->get();
        $selectedMenuItem = MenuItem::with('files')->findOrFail($menuItemId);
        $this->statisticsService->logViewItem($user->id, $menuItemId);
        $this->statisticsService->logDownload($user->id, $menuItemId);
        return view('user.menu.index', compact('menuItems', 'selectedMenuItem'));
    }


    // You might want to create a method in User model to fetch accessible menu items
    // Example method inside User model:
    // public function accessibleMenuItems() {
    //     // Fetch menu items based on user permissions
    //     return MenuItem::where('condition', 'your_logic_here')->get();
    // }
}
