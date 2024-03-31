<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupPermission;
use App\Models\MenuItems\MenuItem;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.autos.index');
    }

    // public function assignOrUpdatePermissions(Request $request)
    // {
    //     $validated = $request->validate([
    //         'menu_id' => 'required|exists:menu_items,id',
    //         'entity_id' => 'required',
    //         'entity_type' => 'required|in:user,group',
    //     ]);

    //     $menuId = $validated['menu_id'];
    //     $entityId = $validated['entity_id'];
    //     $entityType = $validated['entity_type'];

    //     if ($entityType === 'user') {
    //         $this->assignOrUpdateUserPermission($menuId, $entityId);
    //     } elseif ($entityType === 'group') {
    //         $this->assignOrUpdateGroupPermissions($menuId, $entityId);
    //     }

    //     return back()->with('success', 'Permissions assigned/updated successfully.');
    // }

    // protected function assignOrUpdateUserPermission($menuId, $userId)
    // {
    //     Permission::updateOrCreate(
    //         ['menu_item_id' => $menuId, 'user_id' => $userId]
    //     );
    // }

    // protected function assignOrUpdateGroupPermissions($menuId, $groupId)
    // {
    //     $users = User::where('users_groups_id', $groupId)->get();

    //     foreach ($users as $user) {
    //         Permission::updateOrCreate(
    //             ['menu_item_id' => $menuId, 'user_id' => $user->id]
    //         );
    //     }
    // }

    public function updateGroupPermission(Request $request)
    {
        \Log::debug($request->all());

        $request->validate([
            'menu_id' => 'required|integer',
            'group_id' => 'required|integer',
            'action' => 'required|in:assign,remove',
        ]);

        $menuId = $request->menu_id;
        $groupId = $request->group_id;
        $action = $request->action;

        if ($action === 'assign') {
            GroupPermission::updateOrCreate(
                ['menu_item_id' => $menuId, 'user_group_id' => $groupId]
            );
        } else {
            GroupPermission::where('menu_item_id', $menuId)
                        ->where('user_group_id', $groupId)
                        ->delete();
        }

        Log::info('Permission was updated');
        return response()->json(['message' => 'Group permissions updated successfully.']);
    }
    public function updateUserPermission(Request $request)
    {
        Log::info('updateUserPermission', $request->all());
        $validated = $request->validate([
            'menu_id' => 'required|integer',
            'user_id' => 'required|integer',
            'action' => 'required|in:assign,remove',
        ]);

        $menuId = $validated['menu_id'];
        $userId = $validated['user_id'];
        $action = $validated['action'];

        if ($action === 'assign') {
            Permission::updateOrCreate(
                ['menu_item_id' => $menuId, 'user_id' => $userId]
            );
        } else {
            Permission::where('menu_item_id', $menuId)
                      ->where('user_id', $userId)
                      ->delete();
        }

        return response()->json(['message' => 'User permissions updated successfully.']);
    }
}
