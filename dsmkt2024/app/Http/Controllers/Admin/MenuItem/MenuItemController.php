<?php

namespace App\Http\Controllers\Admin\MenuItem;

use App\Contracts\IApplication;
use App\Contracts\IStatistics;
use App\Http\Controllers\Controller;
use App\Models\MenuItems\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuItemController extends Controller
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
        $menuItems = MenuItem::get()->toTree();
        return view('menu.index', compact('menuItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'parent_id' => 'nullable'
        ]);

        if (empty($validated['parent_id']) || $validated['parent_id'] === 'NULL') {
            $menuItem = new MenuItem(['name' => $validated['name']]);
            $menuItem->saveAsRoot();

            session()->flash('success', 'New root menu item created successfully.');
        } else {
            $parentItem = MenuItem::find($validated['parent_id']);
            if ($parentItem) {
                $menuItem = new MenuItem($validated);
                $menuItem->appendTo($parentItem)->save();

                session()->flash('success', 'New child menu item created successfully and appended to the parent.');
            } else {
                session()->flash('error', 'Parent item not found.');
                return back()->withInput();
            }
        }

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

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

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

        return redirect()->route('menu');
    }

    public function updateOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $menuItem = MenuItem::find($request->item_id);
            if (!$menuItem) {
                return response()->json(['success' => false, 'message' => 'Menu item not found'], 404);
            }

            $newPosition = $request->position;
            $newParentId = $request->parent_id;

            if ($newParentId !== null && $menuItem->parent_id != $newParentId) {
                $newParent = MenuItem::find($newParentId);
                if (!$newParent && $newParentId != 'NULL' && $newParentId != '0') {
                    return response()->json(['success' => false, 'message' => 'New parent item not found'], 404);
                }
                $menuItem->parent_id = $newParentId === 'NULL' || $newParentId === '0' ? null : $newParentId;
            }

            $siblings = MenuItem::where('parent_id', $menuItem->parent_id)->orderBy('position')->get();
            $siblings->where('id', '!=', $menuItem->id)->each(function ($sibling, $index) use ($newPosition, $menuItem) {
                if ($index >= $newPosition) {
                    $sibling->position = $index + 2;
                } else {
                    $sibling->position = $index + 1;
                }
                $sibling->save();
            });

            $menuItem->position = $newPosition + 1;
            $menuItem->save();

            DB::commit();

            $this->statisticsService->logUserActivity(auth()->id(), [
                'uri' => $request->path(),
                'post_string' => $request->except('_token'),
                'query_string' => $request->getQueryString(),
            ]);

            return response()->json(['success' => true, 'message' => 'Menu item order updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update menu item order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update the menu item order'], 500);
        }
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
        $newPosition = $request->position ?? 0;

        $menuItem = MenuItem::find($itemId);
        if (!$menuItem) {
            Log::error('Menu item not found with id: ' . $itemId);
            return response()->json(['error' => 'Menu item not found'], 404);
        }

        try {
            DB::beginTransaction();

            if (is_null($newParentId) || $newParentId === "#") {
                Log::info('Makeing the new item as the the root.');
                $menuItem->makeRoot();
                $menuItem->parent_id = null;
                $menuItem->save();
            } else {
                $newParent = MenuItem::find($newParentId);
                if (!$newParent) {
                    DB::rollBack();
                    return response()->json(['error' => 'New parent item not found'], 404);
                }

                $menuItem->appendTo($newParent)->save();
            }

            $this->updateOrderInternal($menuItem, $newPosition, $newParentId);

            Log:info('MenuItem before the commit:', $menuItem->toArray());

            DB::commit();
            Log::info('Menu item moved successfully.');

            $this->statisticsService->logUserActivity(auth()->id(), [
                'uri' => $request->path(),
                'post_string' => $request->except('_token'),
                'query_string' => $request->getQueryString(),
            ]);

            return response()->json(['success' => true, 'message' => 'Menu item moved successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update the tree structure: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update the tree structure: ' . $e->getMessage()], 500);
        }
    }

    private function updateOrderInternal($menuItem, $newPosition, $newParentId)
    {
        $currentParentId = $menuItem->parent_id;
        $isNewParent = $currentParentId != $newParentId;

        $queryParentId = $isNewParent ? $newParentId : $currentParentId;
        $siblings = MenuItem::where('parent_id', $queryParentId)
                            ->orderBy('position', 'asc')
                            ->get();

        if ($isNewParent) {
            $menuItem->position = $newPosition;
        } else {
            $siblings = $siblings->filter(function ($sib) use ($menuItem) {
                return $sib->id != $menuItem->id;
            })->values();
        }

        $siblings->splice($newPosition, 0, [$menuItem]);

        foreach ($siblings as $index => $sib) {
            $sib->position = $index;
            $sib->save();
        }

        if ($isNewParent) {
            $menuItem->parent_id = $newParentId === 'NULL' || $newParentId === '0' ? null : $newParentId;
            $menuItem->save();
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
