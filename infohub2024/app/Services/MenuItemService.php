<?php

namespace App\Services;

use App\Contracts\IMenuItemService;
use App\Models\MenuItems\MenuItem;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MenuItemService implements IMenuItemService
{
    public function getMenuItemsToSelect(MenuItem $menuItem = null)
    {
        return MenuItem::all()->except(optional($menuItem)->id);
    }

    public function getUsersWithOwners(MenuItem $menuItem = null)
    {
        $users = User::where('active', 1)->get();
        $currentOwners = $menuItem ? $menuItem->owners->pluck('id')->toArray() : [];
        $nonOwners = $users->whereNotIn('id', $currentOwners);

        return compact('users', 'currentOwners', 'nonOwners');
    }


    public function updateMenuItemOwners(MenuItem $menuItem, $ownerIds)
    {
        $ownerIds = json_decode($ownerIds, true);
        if (is_array($ownerIds)) {
            $ownerIds = array_map('intval', array_filter($ownerIds));
            Log::debug('Decoded and filtered owner IDs', ['owner_ids' => $ownerIds]);
        } else {
            Log::error('Invalid owners field format', ['owners' => $ownerIds]);
            throw new \InvalidArgumentException('Invalid owners data provided');
        }

        if (!empty($ownerIds)) {
            $menuItem->owners()->sync($ownerIds);
            Log::info('Owners synced successfully');
        } else {
            $menuItem->owners()->detach();
            Log::info('All owners detached due to empty input');
        }
    }

    public function deleteSubMenuItems(MenuItem $menuItem)
    {
        foreach ($menuItem->children as $child) {
            $this->deleteSubMenuItems($child);
        }

        $directory = "menu_files/{$menuItem->id}";
        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        $menuItem->delete();
    }

    public function updateTreeStructure(MenuItem $menuItem, $newParentId)
    {
        if (is_null($newParentId)) {
            if (!$menuItem->isRoot()) {
                $menuItem->makeRoot();
            }
        } else {
            $newParent = MenuItem::find($newParentId);
            if ($newParent) {
                $menuItem->appendTo($newParent)->save();
            }
        }
    }
}
