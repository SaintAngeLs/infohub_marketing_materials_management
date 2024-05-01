<?php

namespace App\Contracts;

use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;

interface IMenuItemService
{
    public function getAllMenuItems();
    public function createMenuItem(array $data);
    public function updateMenuItem(int $id, array $data);
    public function deleteMenuItem(int $id);
}
