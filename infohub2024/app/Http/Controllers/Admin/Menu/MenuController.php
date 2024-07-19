<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Contracts\IApplication;
use App\Contracts\IMenuItemService;
use App\Contracts\IStatistics;
use App\Helpers\FormatBytes;
use App\Http\Controllers\Admin\GroupPermission;
use App\Http\Controllers\Controller;
use App\Models\MenuItems\MenuItem;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    protected $applicationService;
    protected $statisticsService;
    protected $menuItemService;

    public function __construct(IApplication $applicationService, IStatistics $statisticsService, IMenuItemService $menuItemService)
    {
        $this->applicationService = $applicationService;
        $this->statisticsService = $statisticsService;
        $this->menuItemService = $menuItemService;
    }

    public function index()
    {
        $menuItems = MenuItem::getOrderedMenuItems();

        return view('admin.menu.index');
    }

    public function indexFiles()
    {
        $menuItems = MenuItem::getOrderedMenuItems();
        $formattedMenuItems = $this->formatMenuItemsWithFilesForTable($menuItems);

        // Log the formatted menu items
        Log::debug('Formatted Menu Items:', ['formattedMenuItems' => $formattedMenuItems]);

        return view('admin.files.index', compact('formattedMenuItems'));
    }
    public function create()
    {
        $menuItemsToSelect = $this->menuItemService->getMenuItemsToSelect();
        $usersData = $this->menuItemService->getUsersWithOwners();

        return view('admin.menu.create', array_merge(['menuItemsToSelect' => $menuItemsToSelect], $usersData));
    }


    public function edit(MenuItem $menuItem)
    {
        $menuItemsToSelect = $this->menuItemService->getMenuItemsToSelect($menuItem);
        $usersData = $this->menuItemService->getUsersWithOwners($menuItem);

        return view('admin.menu.edit', array_merge(['menuItemsToSelect' => $menuItemsToSelect, 'menuItem' => $menuItem], $usersData));
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->menuItemService->deleteSubMenuItems($menuItem);
        return response()->json(['success' => 'Menu item and all associated data have been deleted.']);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        Log::debug('Update function', $request->all());
        Log::debug($menuItem->toArray());

        $validatedData = $this->validateMenuItem($request);

        // Update owners
        $this->menuItemService->updateMenuItemOwners($menuItem, $request->input('owners', '[]'));

        $menuItem->fill($validatedData);
        $menuItem->save();

        $this->updateMenuItemPermissions($menuItem, $request->input('menu_permissions', []));
        $this->menuItemService->updateTreeStructure($menuItem, $validatedData['parent_id']);

        $this->logUserActivity($request);

        return redirect()->route('menu.structure')->with('success', 'Menu item updated successfully.');
    }

    protected function validateMenuItem(Request $request)
    {
        return $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'visibility_start' => 'nullable|date',
            'visibility_end' => 'nullable|date',
            'banner' => 'required|string',
            'menu_permissions' => 'nullable|array',
        ]);
    }

    protected function updateMenuItemPermissions(MenuItem $menuItem, array $permissions)
    {
        $menuItem->users()->sync($permissions);
    }

    protected function logUserActivity(Request $request)
    {
        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);
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

    public function getMenuItemPermissions($groupId = null)
    {
        $menuItems = MenuItem::getOrderedMenuItems();
        $permissions = !is_null($groupId) ? GroupPermission::where('user_group_id', $groupId)
            ->pluck('menu_item_id')
            ->toArray() : [];

        $formattedMenuItems = $this->formatMenuItemsForPermissionsTable($menuItems, $permissions);

        return view('admin.menu.permissions', compact('formattedMenuItems', 'groupId'));
    }


    protected function formatForJsTree($menuItems)
{
    $formatted = [];
    foreach ($menuItems as $item) {
        $status = $item->status ? 'Aktywny' : 'Nieaktywny';

        $ownerNames = $item->owners->map(function($owner) {
            $userGroup = $owner->usersGroup ? $owner->usersGroup->name : 'N/A';
            $branch = $owner->branch ? $owner->branch->name : 'N/A';
            return "{$owner->name} {$owner->surname} ({$userGroup} - {$branch})";
        })->implode('<br>');

        $ownerNameDisplay = !empty($ownerNames) ? $ownerNames : 'N/A';
        $visibilityTime = $item->start && $item->end
            ? $item->start->format('d.m.Y') . ' do ' . $item->end->format('d.m.Y')
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

    protected function formatMenuItemsWithFilesForTable($menuItems)
    {
        $formatted = [];
        foreach ($menuItems as $item) {
            $status = $item->status ? 'Aktywny' : 'Nieaktywny';

            $ownerDetails = $item->owners->map(function($owner) {
                $userGroup = $owner->usersGroup ? $owner->usersGroup->name : 'N/A';
                $branch = $owner->branch ? $owner->branch->name : 'N/A';
                return "{$owner->name} {$owner->surname} ({$userGroup} - {$branch})";
            })->implode('<br>');

            $ownerDisplay = !empty($ownerDetails) ? $ownerDetails : 'N/A';
            $visibilityTime = $item->start && $item->end
                ? $item->start->format('d.m.Y') . ' do ' . $item->end->format('d.m.Y')
                : 'N/A';

            $files = $item->files;
            $fileDetails = [];
            foreach ($files as $file) {
                $status = $file->status ? "Wł" : "Wył";
                $lastUpdate = $file->updated_at ? $file->updated_at->format('d.m.Y H:i:s') : 'N/A';
                $fileExtension = $file->extension ?? 'unknown';
                $fileSize = FormatBytes::formatBytes($file->weight);
                $start = $file->start ? $file->start->format('d.m.Y') : '-';
                $end = $file->end ? $file->end->format('d.m.Y') : '-';
                $visibility = "$start - $end";
                $fileDetails[] = [
                    'status' => $status,
                    'name' => $file->name,
                    'extension' => $fileExtension,
                    'size' => $fileSize,
                    'visibility' => $visibility,
                    'lastUpdate' => $lastUpdate,
                    'id' => $file->id,
                ];
            }

            $formatted[] = [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $status,
                'owners' => $ownerDisplay,
                'visibility' => $visibilityTime,
                'files' => $fileDetails,
                'children' => $item->children->isEmpty() ? [] : $this->formatMenuItemsWithFilesForTable($item->children),
            ];
        }
        return $formatted;
    }



    protected function formatMenuItemsForPermissionsTable($menuItems, $permissions)
    {
        $formatted = [];
        foreach ($menuItems as $item) {
            $checked = in_array($item->id, $permissions) ? "checked" : "";
            $formatted[] = [
                'id' => $item->id,
                'name' => $item->name,
                'checked' => $checked,
                'children' => $item->children->isEmpty() ? [] : $this->formatMenuItemsForPermissionsTable($item->children, $permissions),
            ];
        }
        return $formatted;
    }
}
