<?php

namespace App\Contracts;

use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;

interface IMenuItemService
{
    public function getMenuItemsToSelect(MenuItem $menuItem = null);
    public function getUsersWithOwners(MenuItem $menuItem = null);
    public function updateMenuItemOwners(MenuItem $menuItem, $ownerIds);
    public function deleteSubMenuItems(MenuItem $menuItem);
    public function updateTreeStructure(MenuItem $menuItem, $newParentId);
}
