<?php

namespace App\Contracts;

use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;

interface IMenuItemTreeElementService
{
    public function getMenuItems();
    public function createMenuItem(array $data);
    public function updateMenuItem(MenuItem $menuItem, array $data);
    public function updateOrder(Request $request);
    public function updateType(Request $request);
    public function updateTreeStructure(Request $request);
    public function hasSubItems($id);
    public function deleteMenuItem(MenuItem $menuItem);
}
