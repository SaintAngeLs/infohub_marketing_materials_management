<?php

namespace App\Services;

use App\Contracts\IPermissionService;
use App\Models\GroupPermission;
use App\Models\Permission;
use Carbon\Carbon;

class PermissionService implements IPermissionService
{
    /**
     * Update or assign permissions for a group.
     *
     * @param integer $menuId Menu item ID
     * @param integer $groupId Group ID
     * @param string $action Action to perform (assign/remove)
     * @return void
     */
    public function updateGroupPermission($menuId, $groupId, $action)
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');

        if ($action === 'assign') {
            GroupPermission::updateOrCreate(
                ['menu_item_id' => $menuId, 'user_group_id' => $groupId],
                ['created_at' => $timestamp, 'updated_at' => $timestamp]
            );
        } else if ($action === 'remove') {
            GroupPermission::where('menu_item_id', $menuId)
                           ->where('user_group_id', $groupId)
                           ->delete();
        }
    }

    /**
     * Update or assign permissions for a user.
     *
     * @param integer $menuId Menu item ID
     * @param integer $userId User ID
     * @param string $action Action to perform (assign/remove)
     * @return void
     */
    public function updateUserPermission($menuId, $userId, $action)
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');

        if ($action === 'assign') {
            Permission::updateOrCreate(
                ['menu_item_id' => $menuId, 'user_id' => $userId],
                ['created_at' => $timestamp, 'updated_at' => $timestamp]
            );
        } else if ($action === 'remove') {
            Permission::where('menu_item_id', $menuId)
                      ->where('user_id', $userId)
                      ->delete();
        }
    }
}
