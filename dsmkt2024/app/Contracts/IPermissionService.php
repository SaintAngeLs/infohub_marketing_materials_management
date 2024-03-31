<?php

namespace App\Contracts;

interface IPermissionService {
    public function getPermissions($entityId);
    public function formatPermissionsForJsTree($menuItems, $permissions);
}
