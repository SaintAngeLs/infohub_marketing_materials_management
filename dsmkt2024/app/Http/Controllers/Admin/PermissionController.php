<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function assignOrUpdatePermissions(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menu_items,id',
            'entity_id' => 'required',
            'entity_type' => 'required|in:user,group',
        ]);

        $menuId = $validated['menu_id'];
        $entityId = $validated['entity_id'];
        $entityType = $validated['entity_type'];

        if ($entityType === 'user') {
            $this->assignOrUpdateUserPermission($menuId, $entityId);
        } elseif ($entityType === 'group') {
            $this->assignOrUpdateGroupPermissions($menuId, $entityId);
        }

        return back()->with('success', 'Permissions assigned/updated successfully.');
    }

    protected function assignOrUpdateUserPermission($menuId, $userId)
    {
        Permission::updateOrCreate(
            ['menu_item_id' => $menuId, 'user_id' => $userId]
        );
    }

    protected function assignOrUpdateGroupPermissions($menuId, $groupId)
    {
        $users = User::where('users_groups_id', $groupId)->get();

        foreach ($users as $user) {
            Permission::updateOrCreate(
                ['menu_item_id' => $menuId, 'user_id' => $user->id]
            );
        }
    }

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

        $userIds = User::where('users_groups_id', $groupId)->pluck('id');

        if ($action === 'assign') {
            foreach ($userIds as $userId) {
                Permission::updateOrCreate(
                    ['menu_item_id' => $menuId, 'user_id' => $userId]
                );
            }
        } else {
            Permission::where('menu_item_id', $menuId)
                    ->whereIn('user_id', $userIds)
                    ->delete();
        }

        return response()->json(['message' => 'Permissions updated successfully.']);
    }
}
