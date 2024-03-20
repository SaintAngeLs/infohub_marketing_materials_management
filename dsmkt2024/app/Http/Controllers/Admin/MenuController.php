<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems_to_select = MenuItem::all();
        return view('menu.index', compact('menuItems_to_select'));
    }

    public function create()
    {
        $menuItems_to_select = MenuItem::all();
        return view('menu.create', compact('menuItems_to_select'));
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
            $formattedItem = [
                'id' => $item->id,
                'text' => $item->name,
                'children' => $item->children->isEmpty() ? [] : $this->formatForJsTree($item->children)
            ];
            $formatted[] = $formattedItem;
        }
        return $formatted;
    }

}
