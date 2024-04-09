<?php

namespace App\Services;

use App\Contracts\IMenuItemService;
use App\Models\MenuItems\MenuItem;
use Illuminate\Support\Facades\Log;

class MenuItemService implements IMenuItemService
{
    public function getAllMenuItems()
    {
        return MenuItem::get()->toTree();
    }

    public function createMenuItem(array $data)
    {
        $menuItem = new MenuItem($data);
        if (isset($data['parent_id']) && !empty($data['parent_id'])) {
            $parent = MenuItem::find($data['parent_id']);
            if ($parent) {
                $menuItem->appendToNode($parent)->save();
            }
        } else {
            $menuItem->saveAsRoot();
        }
        return $menuItem;
    }

    public function updateMenuItem(int $id, array $data)
    {
        $menuItem = MenuItem::findOrFail($id);
        $menuItem->update($data);
        if (isset($data['parent_id']) && !empty($data['parent_id'])) {
            $parent = MenuItem::find($data['parent_id']);
            if ($parent) {
                $menuItem->appendToNode($parent)->save();
            }
        }
    }

    public function deleteMenuItem(int $id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $menuItem->delete();
    }
}
