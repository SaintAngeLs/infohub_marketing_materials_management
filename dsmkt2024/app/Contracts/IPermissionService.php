<?php

namespace App\Contracts;

interface IPermissionService
{
    public function updateGroupPermission($menuId, $groupId, $action);
    public function updateUserPermission($menuId, $userId, $action);
}
