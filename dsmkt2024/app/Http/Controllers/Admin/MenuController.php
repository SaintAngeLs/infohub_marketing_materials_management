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
        return view('admin.menu.edit', compact('menuItemsToSelect', 'menuItem', 'users'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        Log::debug('Update function', $request->all());
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
            'menu_permissions' => 'required|array',
        ]);

        $validatedData['parent_id'] = $validatedData['parent_id'] === 'NULL' ? null : $validatedData['parent_id'];

        $menuItem->fill($validatedData);

        $menuItem->save();

        if (isset($validatedData['owners'])) {
            $menuItem->owners()->sync($validatedData['owners']);
        }

        $this->updateMenuItemPermissions($menuItem, $request->input('menu_permissions', []));

        $this->updateTreeStructureAfterMenuUpdate($menuItem, $validatedData['parent_id']);

        return redirect()->route('menu.structure')->with('success', 'Menu item updated successfully.');
    }

    protected function updateMenuItemPermissions(MenuItem $menuItem, array $permissions)
    {
        $menuItem->users()->sync($permissions);
    }

    protected function updateTreeStructureAfterMenuUpdate(MenuItem $menuItem, $newParentId)
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

    public function getMenuItemWithPermissions()
    {
        $menuItems = MenuItem::get()->toTree();
        $formattedMenuItems = $this->formatForJsTreePermissions($menuItems);
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
            ];
            $formatted[] = $formattedItem;
        }
        return $formatted;
    }

    public function formatForJsTreePermissions($menuItems)
    {
        $formatted = [];
        foreach ($menuItems as $item) {
            $checkboxHtml = "<input type='checkbox' class='menu-item-checkbox' name='menu_permissions[{$item->id}]' id='menu_permission_{$item->id}' value='{$item->id}' />";

            $nodeContent = <<<HTML
                <div class='js-tree-node-content' data-node-id="{$item->id}">
                    <span class='node-name'>{$item->name}</span>
                    <span class='node-checkbox'>$checkboxHtml</span>
                </div>
            HTML;

            $formattedItem = [
                'id' => $item->id,
                'text' => $nodeContent,
                'children' => $item->children->isEmpty() ? [] : $this->formatForJsTreePermissions($item->children),
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
            $files = $item->files;
            $fileDetails = '';
            foreach ($files as $file) {
                    $status = $file->status ? "<span class='toggle-file-status' data-file-id='{$file->id}' style='cursor:pointer;'>Wł</span>"
                                            : "<span class='toggle-file-status' data-file-id='{$file->id}' style='cursor:pointer;'>Wył</span>";
                    $lastUpdate = $file->updated_at ? $file->updated_at->format('d.m.Y H:i:s') : 'N/A';
                    $start = $file->start ? $file->start->format('d.m.Y') : '-';
                    $end = $file->end ? $file->end->format('d.m.Y') : '-';
                    $visibility = "$start - $end";
                    $fileDetails .= <<<HTML

                        <span>$status</span>
                        <span><a href='#' class='file-link' data-file-id='{$file->id}'>{$file->name}</a></span>
                        <span>$visibility</span>
                        <span>$lastUpdate</span>
                        <span>
                            <button onclick="downloadFile({$file->id})" class="btn btn-sm download-file-btn" data-file-id="{$file->id}">pobierz</button>
                            <button onclick="deleteFile({$file->id})" class="btn btn-sm delete-file-btn" data-file-id="{$file->id}">usuń</button>

                        </span>
                HTML;
            }
            $nodeContent = <<<HTML
                <div class="js-tree-files-node-info">
                    <div class='js-tree-node-content' data-node-id="{$item->id}">
                        <span class='node-name'>{$item->name}</span>
                        <span class='node-details-status'>($status)</span>
                        <span class='node-details-ownerName'>{$ownerName}</span>
                        <span class='node-details-visibilityTime'>{$visibilityTime}</span>
                        <button onclick="openFileUploadPage({$item->id})" class="btn btn-sm upload-file-btn">Upload File</button>
                    </div>

                    <div class='files-data'>
                        <div class='js-tree-node-files' data-node-id="{$item->id}">
                            $fileDetails
                        </div>
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
