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

        // If 'parent_id' is not provided or explicitly set to 'null', create a new root menu item
        if (empty($validated['parent_id']) || $validated['parent_id'] === 'NULL') {
            $menuItem = new MenuItem(['name' => $validated['name']]);
            $menuItem->saveAsRoot();  // This ensures a new tree is started with this item as the root

            session()->flash('success', 'New root menu item created successfully.');
        } else {
            // If 'parent_id' is provided, attempt to find the parent and append this item as a child
            $parentItem = MenuItem::find($validated['parent_id']);
            if ($parentItem) {
                $menuItem = new MenuItem($validated);
                $menuItem->appendTo($parentItem)->save();  // Append this item to the found parent

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
        $menuItem = MenuItem::find($request->item_id);
        $newParentId = $request->parent_id;

        if ($newParentId == 'NULL' || $newParentId == '0') {
            $menuItem->makeRoot()->save();
        } else if (!empty($newParentId)) {
            $parentItem = MenuItem::find($newParentId);
            $menuItem->appendTo($parentItem)->save();
        }
        return response()->json(['success' => true]);
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

        $menuItem = MenuItem::find($itemId);
        if (!$menuItem) {
            Log::error('Menu item not found with id: ' . $itemId);
            return response()->json(['error' => 'Menu item not found'], 404);
        }

        try {
            DB::beginTransaction();

            if (is_null($newParentId) || $newParentId === "#") {
                // There is a possible chagne for the unsave here --> need tom try
                // catch to check it should be Root and it is not Root
                Log::info('Makeing the new item as the the root.');
                $menuItem->makeRoot();
                // Direct intention to set the paren_id to NULL value
                $menuItem->parent_id = null;
                $menuItem->save();
            } else {
                $newParent = MenuItem::find($newParentId);
                if (!$newParent) {
                    DB::rollBack();
                    return response()->json(['error' => 'New parent item not found'], 404);
                }

                // Move the item to its new parent and save
                $menuItem->appendTo($newParent)->save();
            }

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
