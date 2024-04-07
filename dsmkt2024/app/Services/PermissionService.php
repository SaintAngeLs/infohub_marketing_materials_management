<?php

namespace App\Services;

use App\Contracts\IPermissionService;
use App\Models\GroupPermission;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

     /**
     * Copies permissions from one user to another.
     *
     * @param integer $sourceUserId ID of the user to copy permissions from
     * @param integer $targetUserId ID of the user to copy permissions to
     * @return void
     */
    public function copyUserPermissions($sourceUserId, $targetUserId)
    {
        DB::transaction(function () use ($sourceUserId, $targetUserId) {
            $sourcePermissions = Permission::where('user_id', $sourceUserId)->get();

            Permission::where('user_id', $targetUserId)->delete();

            foreach ($sourcePermissions as $permission) {
                Permission::create([
                    'user_id' => $targetUserId,
                    'menu_item_id' => $permission->menu_item_id,
                ]);
            }
        });
    }
}
