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
        $autos = Auto::all();

        return view('admin.files.create', compact('menuItemsToSelect', 'autos'));
    }

    public function edit($id)
    {
        $file = File::findOrFail($id);
        $menuItemsToSelect = MenuItem::all();
        $autos = Auto::all();

        return view('admin.files.edit', compact('file', 'menuItemsToSelect', 'autos'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'name' => 'required|string|max:255',
            'file_location' => 'required|in:disk,external,server',
            'file' => 'required_if:file_location,disk|file|max:716800', // 700MB in kilobytes
            'external_url' => 'required_if:file_location,external|url',
            'server_path' => 'required_if:file_location,server|string',
            'visible_from' => 'nullable|date',
            'visible_to' => 'nullable|date',
            'tags' => 'nullable|string',
            'auto_id' => 'nullable|exists:autos,id',
        ]);

        $fileModel = new File();
        $fileModel->menu_item_id = $validated['menu_item_id'];
        $fileModel->name = $validated['name'];

        // Handling file based on location
        switch ($validated['file_location']) {
            case 'disk':
                // Storing file locally
                $fileModel->path = $request->file('file')->store('menu_files');
                break;
            case 'external':
                // Saving external URL
                $fileModel->path = $validated['external_url'];
                break;
            case 'server':
                // Using an existing server file path
                $fileModel->path = $validated['server_path'];
                break;
        }

        $fileModel->visible_from = $validated['visible_from'] ?? null;
        $fileModel->visible_to = $validated['visible_to'] ?? null;
        $fileModel->tags = $validated['tags'] ?? null;
        $fileModel->auto_id = $validated['auto_id'] ?? null;
        $fileModel->save();

        return redirect()->route('menu.files')->with('success', 'File added successfully to the selected tab.');
    }
}
