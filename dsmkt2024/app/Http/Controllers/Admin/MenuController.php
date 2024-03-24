<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItems\MenuItem;
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
        $menuItems_to_select = MenuItem::all();
        return view('admin.menu.create', compact('menuItems_to_select'));
    }

    public function edit(MenuItem $menuItem)
    {
        $menuItems_to_select = MenuItem::all();
        return view('admin.menu.edit', compact('menuItems_to_select'));
    }

    public function toggleStatus(Request $request, $menuItem)
    {
        $menuItem = MenuItem::find($menuItem);
        if (!$menuItem) {
            return response()->json(['error' => 'Menu item not found.'], 404);
        }

        // Toggle the status
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
