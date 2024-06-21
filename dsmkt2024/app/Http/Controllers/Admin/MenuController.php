<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\IApplication;
use App\Contracts\IStatistics;
use App\Helpers\FormatBytes;
use App\Http\Controllers\Controller;
use App\Models\ExtendedUser;
use App\Models\GroupPermission;
use App\Models\MenuItems\MenuItem;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    protected $applicationService;
    protected $statisticsService;

    public function __construct(IApplication $applicationService, IStatistics $statisticsService)
    {
        $this->applicationService = $applicationService;
        $this->statisticsService = $statisticsService;
    }
    public function index()
    {
        $menuItems = MenuItem::getOrderedMenuItems();
        return view('admin.menu.index', compact('menuItems'));
    }

    public function create()
    {
        $menuItemsToSelect = MenuItem::all();
        $users = User::where('active', 1)->get();
        return view('admin.menu.create', compact('menuItemsToSelect', 'users'));
    }


    public function edit(MenuItem $menuItem)
    {
        $menuItemsToSelect = MenuItem::all()->except($menuItem->id);
        $users = User::where('active', 1)->get();
        return view('admin.menu.edit', compact('menuItemsToSelect', 'menuItem', 'users'));
    }

    public function destroy(Request $request, MenuItem $menuItem)
    {
        $this->deleteSubMenuItems($menuItem);

        $menuItem->delete();

        return response()->json(['success' => 'Menu item and all associated data have been deleted.']);
    }

    protected function deleteSubMenuItems(MenuItem $menuItem)
    {
        foreach ($menuItem->children as $child) {
            $this->deleteSubMenuItems($child);
        }

        $directory = "menu_files/{$menuItem->id}";
        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        $menuItem->delete();
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        Log::debug('Update function', $request->all());
        Log::debug($menuItem->toArray());

        Log::debug('Attempting to sync owners');
        Log::debug('Before syncing owners', ['Owners' => $request->owners]);

        if (!empty($request['owners'])) {
            Log::debug('Attempting to sync owners', ['Owners' => $request['owners']]);
            $menuItem->owners()->sync($request['owners']);

            Log::debug('Owners synced');
        } else {
            Log::debug('No owners provided, detaching any existing relations');
            $menuItem->owners()->detach();
        }

        $validatedData = $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'owners' => 'nullable|array',
            'visibility_start' => 'nullable|date',
            'visibility_end' => 'nullable|date',
            'banner' => 'required|string',
            'menu_permissions' => 'nullable|array',
        ]);

        $validatedData['parent_id'] = $validatedData['parent_id'] === 'NULL' ? null : $validatedData['parent_id'];

        if ($request->has('owners')) {
            $menuItem->owners()->sync($request->input('owners'));
        } else {
            $menuItem->owners()->detach();
        }

        $menuItem->fill($validatedData);

        $menuItem->save();

        $this->updateMenuItemPermissions($menuItem, $request->input('menu_permissions', []));

        $this->updateTreeStructureAfterMenuUpdate($menuItem, $validatedData['parent_id']);

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

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
        $menuItems = MenuItem::getOrderedMenuItems();
        $formattedMenuItems = $this->formatForJsTree($menuItems);
        return response()->json($formattedMenuItems);
    }

    public function getMenuItemsWithFiles()
    {
        $menuItems = MenuItem::getOrderedMenuItems();
        $formattedMenuItems = $this->formatMenuItemsWithFilesForJsTree($menuItems);
        return response()->json($formattedMenuItems);
    }
    public function getMenuItemWithGroupPermissions(Request $request)
    {
        Log::info($request->all());
        $groupId = $request->input('group_id');
        $menuItems = MenuItem::get()->toTree();
        $formattedMenuItems = $this->formatForJsTreeGroupPermissions($menuItems, $groupId);
        return response()->json($formattedMenuItems);
    }

    public function getMenuItemWithUserPermissions(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::with('usersGroup.menuItems')->find($userId);
        $menuItems = MenuItem::get()->toTree();

        $userPermissions = Permission::where('user_id', $userId)
                                    ->pluck('menu_item_id')
                                    ->toArray();

        $groupPermissions = optional($user->usersGroup)->menuItems->pluck('id')->toArray() ?? [];

        $allPermissions = array_unique(array_merge($userPermissions, $groupPermissions));

        $formattedMenuItems = $this->formatForJsTreeUserPermissions($menuItems, $allPermissions);
        return response()->json($formattedMenuItems);
    }


    protected function formatForJsTree($menuItems)
    {
        $formatted = [];
        foreach ($menuItems as $item) {
            $status = $item->status ? 'Aktywny' : 'Nieaktywny';
            $ownerNames = $item->owners->pluck('name')->implode('<br>');
            $ownerNameDisplay = !empty($ownerNames) ? $ownerNames : 'N/A';
            $visibilityTime = $item->start && $item->end
                            ? $item->start->format('Y-m-d') . ' do ' . $item->end->format('Y-m-d')
                            : 'N/A';

        $nodeContent = <<<HTML
            <span class='js-tree-node-content' data-node-id="{$item->id}">
                <span class='node-name'>{$item->name}</span>
                <span class='node-details-status'>($status)</span>
                <span class='node-details-ownerName'>{$ownerNameDisplay}</span>
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

    public function formatForJsTreeGroupPermissions($menuItems, $groupId = null)
    {
        $formatted = [];
        $permissions = !is_null($groupId) ? GroupPermission::where('user_group_id', $groupId)
                                   ->pluck('menu_item_id')
                                   ->toArray() : [];
        Log::info($permissions);

        foreach ($menuItems as $item) {
            $checked = in_array($item->id, $permissions) ? "checked='checked'" : "";

            $checkboxHtml = "<input type='checkbox' class='menu-item-checkbox' name='menu_permissions[{$item->id}]' {$checked} id='menu_permission_{$item->id}' value='{$item->id}' onclick='updateGroupPermission(this)' />";

            $nodeContent = <<<HTML
                <div class='js-tree-node-content' data-node-id="{$item->id}">
                    <span class='node-name'>{$item->name}</span>
                    <div class="checkbox-wrapper">
                        <span class='node-checkbox'>$checkboxHtml</span>
                    </div>
                </div>
            HTML;

            $formattedItem = [
                'id' => $item->id,
                'text' => $nodeContent,
                'children' => $item->children->isEmpty() ? [] : $this->formatForJsTreeGroupPermissions($item->children, $groupId),
            ];

            $formatted[] = $formattedItem;
        }
        return $formatted;
    }

    public function formatForJsTreeUserPermissions($menuItems, $userId = null)
    {
        $formatted = [];
        $permissions = $userId ? Permission::where('user_id', $userId)
                                       ->pluck('menu_item_id')
                                       ->toArray() : [];
        Log::info('formatForJsTreeUserPermissions', $permissions);
        foreach ($menuItems as $item) {
            $checked = in_array($item->id, $permissions) ? "checked='checked'" : "";

            $checkboxHtml = "<input type='checkbox' class='menu-item-checkbox' name='user_permissions[{$item->id}]' {$checked} id='user_permission_{$item->id}' value='{$item->id}' />";

            $nodeContent = <<<HTML
                <div class='js-tree-node-content' data-node-id="{$item->id}">
                    <span class='node-name'>{$item->name}</span>
                    <div class="checkbox-wrapper">
                        <span class='node-checkbox'>$checkboxHtml</span>
                    </div>
                </div>
            HTML;

            $formattedItem = [
                'id' => $item->id,
                'text' => $nodeContent,
                'children' => $item->children->isEmpty() ? [] : $this->formatForJsTreeUserPermissions($item->children, $userId),
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
            $ownerNames = $item->owners->pluck('name')->implode(',');
            $ownerNameDisplay = !empty($ownerNames) ? $ownerNames : 'N/A';
            $visibilityTime = $item->start && $item->end
                            ? $item->start->format('Y-m-d') . ' do ' . $item->end->format('Y-m-d')
                            : 'N/A';
            $files = $item->files;
            $fileDetails = '';
            foreach ($files as $file) {
                    $status = $file->status ? "<span class='toggle-file-status' data-file-id='{$file->id}' style='cursor:pointer;'>Wł</span>"
                                            : "<span class='toggle-file-status' data-file-id='{$file->id}' style='cursor:pointer;'>Wył</span>";
                    $lastUpdate = $file->updated_at ? $file->updated_at->format('d.m.Y H:i:s') : 'N/A';
                    $fileExtension = $file->extension ?? 'unknown';
                    $fileSize = FormatBytes::formatBytes($file->weight);
                    $start = $file->start ? $file->start->format('d.m.Y') : '-';
                    $end = $file->end ? $file->end->format('d.m.Y') : '-';
                    $visibility = "$start - $end";
                    $fileDetails .= <<<HTML

                        <span>$status</span>
                        <span><a href='#' class='file-link' data-file-id='{$file->id}'>{$file->name}</a></span>
                        <span>$fileExtension</span>
                        <span>$fileSize</span>
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
                        <span class='node-details-ownerName'>{$ownerNameDisplay}</span>
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
