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
        // Log::debug('An informational message.', [$users]);
        return view('admin.menu.edit', compact('menuItemsToSelect', 'menuItem', 'users'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        Log::debug($request->all());
        Log::debug($menuItem->toArray());

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

        $validatedData['parent_id'] = $validatedData['parent_id'] === 'NULL' ? null : $validatedData['parent_id'];

        // If 'parent_id' is explicitly null, handle separately
        if (is_null($validatedData['parent_id'])) {
            // Directly setting parent_id to null to avoid any model or ORM interference
            $menuItem->parent_id = null;
        } else {
            // Handle normally for non-null cases
            $menuItem->parent_id = $validatedData['parent_id'];
        }

        // $menuItem->update([
        //     'type' => $validatedData['type'],
        //     'name' => $validatedData['name'],
        //     'parent_id' => $validatedData['parent_id'],
        //     'start' => $validatedData['visibility_start'],
        //     'end' => $validatedData['visibility_end'],
        //     'banner' => $validatedData['banner'],
        // ]);

        $menuItem->type = $validatedData['type'];
        $menuItem->name = $validatedData['name'];
        // $menuItem->parent_id = $validatedData['parent_id'];
        $menuItem->start = $validatedData['visibility_start'] ?? null;
        $menuItem->end = $validatedData['visibility_end'] ?? null;
        $menuItem->banner = $validatedData['banner'];

        Log::debug($menuItem->toArray());

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

    public function getMenuItemsWithFiles()
    {
        $menuItems = MenuItem::get()->toTree();
        $formattedMenuItems = $this->formatMenuItemsWithFilesForJsTree($menuItems);
        return response()->json($formattedMenuItems);
    }


    protected function formatForJsTree($menuItems)
    {
        $formatted = [];
        foreach ($menuItems as $item) {
            $status = $item->status ? 'Aktywny' : 'Nieaktywny';
            $ownerName = $item->owner->name ?? 'N/A';
            $visibilityTime = $item->start && $item->end
                            ? $item->start->format('Y-m-d') . ' do ' . $item->end->format('Y-m-d')
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

    protected function formatMenuItemsWithFilesForJsTree($menuItems)
    {
        $formatted = [];
        foreach ($menuItems as $item) {
            $status = $item->status ? 'Aktywny' : 'Nieaktywny';
            $ownerName = $item->owner->name ?? 'N/A';
            $visibilityTime = $item->start && $item->end
                            ? $item->start->format('Y-m-d') . ' do ' . $item->end->format('Y-m-d')
                            : 'N/A';

            // Fetch files related to this menu item
            $files = $item->files;
            $fileLinks = '';
            foreach ($files as $file) {
                // Assuming $file->id gives you the unique identifier for the file
                $fileLinks .= "<li><a href='#' class='file-link' data-file-id='{$file->id}'>{$file->name}</a></li>";
            }
            if (!empty($fileLinks)) {
                $fileLinks = "<ul>$fileLinks</ul>";
            } else {
                $fileLinks = "Brak plików";
            }

            // Including files in node content
            $nodeContent = <<<HTML
                <div class="js-tree-files-node-info">
                    <div class='js-tree-node-content' data-node-id="{$item->id}">
                        <span class='node-name'>{$item->name}</span>
                        <span class='node-details-status'>($status)</span>
                        <span class='node-details-ownerName'>{$ownerName}</span>
                        <span class='node-details-visibilityTime'>{$visibilityTime}</span>
                        <button onclick="openFileUploadPage({$item->id})" class="btn btn-sm upload-file-btn">Upload File</button>
                    </div>

                    <div class='js-tree-node-files' data-node-id="{$item->id}">
                        <span class='node-files'>$fileLinks</span>
                    </div>
                </div>
            HTML;

            $formattedItem = [
                'id' => $item->id,
                'text' => $nodeContent,
                'children' => $item->children->isEmpty() ? [] : $this->formatMenuItemsWithFilesForJsTree($item->children),
            ];
            $formatted[] = $formattedItem;
        }
        return $formatted;
    }

}
