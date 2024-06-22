<?php

namespace App\Services;

use App\Contracts\IMenuItemService;
use App\Contracts\IMenuItemTreeElementService;
use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuItemTreeElementService implements IMenuItemTreeElementService
{
    public function getMenuItems()
    {
        return MenuItem::get()->toTree();
    }

    public function createMenuItem(array $data)
    {
        if (empty($data['parent_id']) || $data['parent_id'] === 'NULL') {
            $menuItem = new MenuItem(['name' => $data['name']]);
            $menuItem->saveAsRoot();
            return 'New root menu item created successfully.';
        } else {
            $parentItem = MenuItem::find($data['parent_id']);
            if ($parentItem) {
                $menuItem = new MenuItem($data);
                $menuItem->appendTo($parentItem)->save();
                return 'New child menu item created successfully and appended to the parent.';
            } else {
                throw new \Exception('Parent item not found.');
            }
        }
    }

    public function updateMenuItem(MenuItem $menuItem, array $data)
    {
        $menuItem->update($data);

        // Parent change handler
        if (isset($data['parent_id']) && !empty($data['parent_id'])) {
            $parentItem = MenuItem::find($data['parent_id']);
            if ($parentItem && $menuItem->parent_id !== $parentItem->id) {
                $menuItem->appendTo($parentItem)->save();
            }
        }
    }

    public function updateOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $menuItem = MenuItem::find($request->item_id);
            if (!$menuItem) {
                throw new \Exception('Menu item not found');
            }

            $newPosition = $request->position;
            $newParentId = $request->parent_id;

            if ($newParentId !== null && $menuItem->parent_id != $newParentId) {
                $newParent = MenuItem::find($newParentId);
                if (!$newParent && $newParentId != 'NULL' && $newParentId != '0') {
                    throw new \Exception('New parent item not found');
                }
                $menuItem->parent_id = $newParentId === 'NULL' || $newParentId === '0' ? null : $newParentId;
            }

            $siblings = MenuItem::where('parent_id', $menuItem->parent_id)->orderBy('position')->get();
            $siblings->where('id', '!=', $menuItem->id)->each(function ($sibling, $index) use ($newPosition, $menuItem) {
                if ($index >= $newPosition) {
                    $sibling->position = $index + 2;
                } else {
                    $sibling->position = $index + 1;
                }
                $sibling->save();
            });

            $menuItem->position = $newPosition + 1;
            $menuItem->save();

            DB::commit();

            return 'Menu item order updated successfully.';
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update menu item order: ' . $e->getMessage());
            throw new \Exception('Failed to update the menu item order');
        }
    }

    public function updateType(Request $request)
    {
        $menuItem = MenuItem::find($request->item_id);
        $menuItem->type = $request->type; // 'main' or 'sub'
        $menuItem->save();
        return 'Menu item type updated successfully.';
    }

    public function updateTreeStructure(Request $request)
    {
        Log::debug('updateTreeStructure called', $request->all());

        $itemId = $request->id;
        $newParentId = $request->parent_id;
        $newPosition = $request->position ?? 0;

        $menuItem = MenuItem::find($itemId);
        if (!$menuItem) {
            Log::error('Menu item not found with id: ' . $itemId);
            throw new \Exception('Menu item not found');
        }

        try {
            DB::beginTransaction();

            if (is_null($newParentId) || $newParentId === "#") {
                Log::info('Making the new item as the root.');
                $menuItem->makeRoot();
                $menuItem->parent_id = null;
                $menuItem->save();
            } else {
                $newParent = MenuItem::find($newParentId);
                if (!$newParent) {
                    DB::rollBack();
                    throw new \Exception('New parent item not found');
                }

                $menuItem->appendTo($newParent)->save();
            }

            $this->updateOrderInternal($menuItem, $newPosition, $newParentId);

            DB::commit();
            Log::info('Menu item moved successfully.');
            return 'Menu item moved successfully.';
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update the tree structure: ' . $e->getMessage());
            throw new \Exception('Failed to update the tree structure: ' . $e->getMessage());
        }
    }

    private function updateOrderInternal($menuItem, $newPosition, $newParentId)
    {
        $currentParentId = $menuItem->parent_id;
        $isNewParent = $currentParentId != $newParentId;

        $queryParentId = $isNewParent ? $newParentId : $currentParentId;
        $siblings = MenuItem::where('parent_id', $queryParentId)
            ->orderBy('position', 'asc')
            ->get();

        if ($isNewParent) {
            $menuItem->position = $newPosition;
        } else {
            $siblings = $siblings->filter(function ($sib) use ($menuItem) {
                return $sib->id != $menuItem->id;
            })->values();
        }

        $siblings->splice($newPosition, 0, [$menuItem]);

        foreach ($siblings as $index => $sib) {
            $sib->position = $index;
            $sib->save();
        }

        if ($isNewParent) {
            $menuItem->parent_id = $newParentId === 'NULL' || $newParentId === '0' ? null : $newParentId;
            $menuItem->save();
        }
    }

    public function hasSubItems($id)
    {
        $menuItem = MenuItem::with('children')->find($id);
        return $menuItem && $menuItem->children->isNotEmpty();
    }

    public function deleteMenuItem(MenuItem $menuItem)
    {
        $menuItem->delete();
        return 'Menu item deleted successfully.';
    }
}
