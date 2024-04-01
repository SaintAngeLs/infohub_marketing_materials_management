<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuItemController extends Controller
{

    /**
     * Display a listing of menu items
     */
    public function index()
    {
        $menuItems = MenuItem::get()->toTree();
        return view('menu.index', compact('menuItems'));
    }

    /**
     * Store data from request parameters
     * If 'parent_id' is provided and not empty, find the parent item and append the new item to it
     * Append the new menu item to the found parent item
     * If parent item is not found, return with an error
     * If parent item is not found, return with an error
     * If 'parent_id' is not provided or empty, it means "No Parent" is selected
     * Check if there's already a root item
     * If no root exists, save the new item as root
     * If a root already exists, return with an error
     * @param request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'parent_id' => 'nullable'
        ]);

        if (empty($validated['parent_id']) || $validated['parent_id'] === 'NULL') {
            $menuItem = new MenuItem(['name' => $validated['name']]);
            $menuItem->saveAsRoot();

            session()->flash('success', 'New root menu item created successfully.');
        } else {
            $parentItem = MenuItem::find($validated['parent_id']);
            if ($parentItem) {
                $menuItem = new MenuItem($validated);
                $menuItem->appendTo($parentItem)->save();

                session()->flash('success', 'New child menu item created successfully and appended to the parent.');
            } else {
                session()->flash('error', 'Parent item not found.');
                return back()->withInput();
            }
        }

        return redirect()->route('menu');
    }


    /**
     * Update the specified menu item
     * @param request
     * @param menuItem -- specified menu item
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $menuItem->update($validated);

        // Parent change handler
        if ($request->has('parent_id') && !empty($request->input('parent_id'))) {
            $parentItem = MenuItem::find($request->input('parent_id'));
            if ($parentItem && $menuItem->parent_id !== $parentItem->id) {
                $menuItem->appendTo($parentItem)->save();
            }
        }

        return redirect()->route('menu');
    }

    public function updateOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $menuItem = MenuItem::find($request->item_id);
            if (!$menuItem) {
                return response()->json(['success' => false, 'message' => 'Menu item not found'], 404);
            }

            $newPosition = $request->position;
            $newParentId = $request->parent_id;

            // Update parent if necessary
            if ($newParentId !== null && $menuItem->parent_id != $newParentId) {
                $newParent = MenuItem::find($newParentId);
                if (!$newParent && $newParentId != 'NULL' && $newParentId != '0') {
                    return response()->json(['success' => false, 'message' => 'New parent item not found'], 404);
                }
                $menuItem->parent_id = $newParentId === 'NULL' || $newParentId === '0' ? null : $newParentId;
            }

            // Adjust positions
            $siblings = MenuItem::where('parent_id', $menuItem->parent_id)->orderBy('position')->get();
            $siblings->where('id', '!=', $menuItem->id)->each(function ($sibling, $index) use ($newPosition, $menuItem) {
                if ($index >= $newPosition) {
                    $sibling->position = $index + 2; // Adjust positions for siblings after the new position
                } else {
                    $sibling->position = $index + 1; // Keep position for siblings before the new position
                }
                $sibling->save();
            });

            $menuItem->position = $newPosition + 1; // Set new position for the moving item
            $menuItem->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Menu item order updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update menu item order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update the menu item order'], 500);
        }
    }

    public function updateType(Request $request)
    {
        $menuItem = MenuItem::find($request->item_id);

        $menuItem->type = $request->type; // 'main' or 'sub'
        $menuItem->save();

        return response()->json(['success' => true]);
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
            return response()->json(['error' => 'Menu item not found'], 404);
        }

        try {
            DB::beginTransaction();

            if (is_null($newParentId) || $newParentId === "#") {
                Log::info('Makeing the new item as the the root.');
                $menuItem->makeRoot();
                $menuItem->parent_id = null;
                $menuItem->save();
            } else {
                $newParent = MenuItem::find($newParentId);
                if (!$newParent) {
                    DB::rollBack();
                    return response()->json(['error' => 'New parent item not found'], 404);
                }

                $menuItem->appendTo($newParent)->save();
            }

            $this->updateOrderInternal($menuItem, $newPosition, $newParentId);

            Log:info('MenuItem before the commit:', $menuItem->toArray());

            DB::commit();
            Log::info('Menu item moved successfully.');
            return response()->json(['success' => true, 'message' => 'Menu item moved successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update the tree structure: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update the tree structure: ' . $e->getMessage()], 500);
        }
    }

    private function updateOrderInternal($menuItem, $newPosition, $newParentId)
{
    // Determine the current and new parent IDs for comparison
    $currentParentId = $menuItem->parent_id;
    $isNewParent = $currentParentId != $newParentId;

    // Fetch all relevant siblings based on the context (same parent or new parent)
    $queryParentId = $isNewParent ? $newParentId : $currentParentId;
    $siblings = MenuItem::where('parent_id', $queryParentId)
                        ->orderBy('position', 'asc')
                        ->get();

    if ($isNewParent) {
        // If moving to a new parent, adjust positions excluding the current item
        $menuItem->position = $newPosition;
    } else {
        // Handle reordering within the same parent
        $siblings = $siblings->filter(function ($sib) use ($menuItem) {
            return $sib->id != $menuItem->id; // Exclude the current item from siblings
        })->values(); // Reset collection keys for proper indexing
    }

    // Insert the menu item in the new position within its siblings
    $siblings->splice($newPosition, 0, [$menuItem]);

    // Update positions for all items in the new order
    foreach ($siblings as $index => $sib) {
        $sib->position = $index;
        $sib->save();
    }

    // If the parent has changed, update the parent_id of the moved item
    if ($isNewParent) {
        $menuItem->parent_id = $newParentId === 'NULL' || $newParentId === '0' ? null : $newParentId;
        $menuItem->save();
    }
}


    public function hasSubItems($id)
    {
        $menuItem = MenuItem::with('children')->find($id);
        $hasSubItems = $menuItem && $menuItem->children->isNotEmpty();
        return response()->json(['hasSubItems' => $hasSubItems]);
    }


    /**
     * Remove the specified menuItem
     * @param menuItem -- menu element to remove
     */
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect()->route('menu');
    }

}
