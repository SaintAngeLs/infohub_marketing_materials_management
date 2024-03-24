<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtendedUser;
use App\Models\MenuItems\MenuItem;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();
        return view('admin.menu.index', compact('menuItems'));
    }

    public function create()
    {
        $menuItemsToSelect = MenuItem::all();
        $users = User::all();
        return view('admin.menu.create', compact('menuItemsToSelect', 'users'));
    }

    public function edit(MenuItem $menuItem)
    {
        $menuItemsToSelect = MenuItem::all()->except($menuItem->id);
        $users = User::all();
        Log::debug('An informational message.', [$users]);
        return view('admin.menu.edit', compact('menuItemsToSelect', 'menuItem', 'users'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'owners' => 'nullable|array',
            'owners.*' => 'exists:users,id',
            'visibility_start' => 'nullable|date',
            'visibility_end' => 'nullable|date',
            'banner' => 'required|string',
        ]);

        $menuItem->update([
            'type' => $validatedData['type'],
            'name' => $validatedData['name'],
            'parent_id' => $validatedData['parent_id'],
            'visibility_start' => $validatedData['visibility_start'],
            'visibility_end' => $validatedData['visibility_end'],
            'banner' => $validatedData['banner'],
        ]);

        if (isset($validatedData['owners'])) {
            $menuItem->owners()->sync($validatedData['owners']);
        }

        return redirect()->route('menu.structure')->with('success', 'Menu item updated successfully.');
    }


    public function toggleStatus(Request $request, $menuItem)
    {
        $menuItem = MenuItem::find($menuItem);
        if (!$menuItem) {
            return response()->json(['error' => 'Menu item not found.'], 404);
        }

        $menuItem->status = !$menuItem->status;
        $menuItem->save();

        return response()->json(['success' => true]);
    }


    public function getMenuItems()
    {
        $menuItems = MenuItem::get()->toTree();
        $formattedMenuItems = $this->formatForJsTree($menuItems);
        return response()->json($formattedMenuItems);
    }

    protected function formatForJsTree($menuItems)
    {
        $formatted = [];
        foreach ($menuItems as $item) {
            $status = $item->status ? 'Visible' : 'Invisible';
            $ownerName = $item->owner->name ?? 'N/A';
            $visibilityTime = $item->start && $item->end
                            ? $item->start->format('Y-m-d') . ' to ' . $item->end->format('Y-m-d')
                            : 'N/A';

        $nodeContent = <<<HTML
            <span class='js-tree-node-content' data-node-id="{$item->id}">
                <span class='node-name'>{$item->name}</span>
                <span class='node-details-status'>($status)</span>
                <span class='node-details-ownerName'>{$ownerName}</span>
                <span class='node-details-visibilityTime'>{$visibilityTime}</span>
            </span>
        HTML;

        $formattedItem = [
            'id' => $item->id,
            'text' => $nodeContent,
                'children' => $item->children->isEmpty() ? [] : $this->formatForJsTree($item->children),
                // Other jsTree node properties
            ];
            $formatted[] = $formattedItem;
        }
        return $formatted;
    }


}
