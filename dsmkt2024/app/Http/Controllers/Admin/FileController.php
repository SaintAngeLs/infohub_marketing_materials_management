<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auto;
use App\Models\File;
use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function create(Request $request)
    {
        // $menuItemId = $request->query('menu_item_id');
        // // Fetch the menu item to pass to the view or perform other logic
        // $menuItem = MenuItem::find($menuItemId);

        // return view('admin.files.create', compact('menuItem'));

        $menuItemsToSelect = MenuItem::all();
        $autos = Auto::all(); // Assuming Auto is your model name for cars

        // If there's other data to pass to the view, add it here
        return view('admin.files.create', compact('menuItemsToSelect', 'autos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'file' => 'required|file|max:716800',
            'visible_from' => 'nullable|date',
            'visible_to' => 'nullable|date',
            'tags' => 'nullable|string',
        ]);

        $fileModel = new File();
        $fileModel->menu_item_id = $validated['menu_item_id'];
        $fileModel->name = $request->file('file')->getClientOriginalName();
        $fileModel->path = $request->file('file')->store('menu_files');
        $fileModel->visible_from = $validated['visible_from'];
        $fileModel->visible_to = $validated['visible_to'];
        $fileModel->tags = $validated['tags'];
        $fileModel->save();

        return back()->with('success', 'File added successfully');
    }
}
