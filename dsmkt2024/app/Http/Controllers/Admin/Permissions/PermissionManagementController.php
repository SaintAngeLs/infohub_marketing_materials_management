<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Contracts\IStatistics;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\IPermissionService;
use Illuminate\Support\Facades\Log;

class PermissionManagementController extends Controller
{
    protected $permissionService;
    protected $statisticsService;

    public function __construct(IPermissionService $permissionService, IStatistics $statisticsService)
    {
        $this->permissionService = $permissionService;
        $this->statisticsService = $statisticsService;
    }

    public function updateGroupPermission(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|integer',
            'group_id' => 'required|integer',
            'action' => 'required|in:assign,remove',
        ]);

        $this->permissionService->updateGroupPermission(
            $validated['menu_id'],
            $validated['group_id'],
            $validated['action']
        );

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

        return response()->json(['message' => 'Group permissions updated successfully.']);
    }

    public function updateUserPermission(Request $request)
    {
        Log::debug('updateGroupPermission', $request->all());
        $validated = $request->validate([
            'menu_id' => 'required|integer',
            'user_id' => 'required|integer',
            'action' => 'required|in:assign,remove',
        ]);

        $this->permissionService->updateUserPermission(
            $validated['menu_id'],
            $validated['user_id'],
            $validated['action']
        );

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

        return response()->json(['message' => 'User permissions updated successfully.']);
    }

    public function copyUserPermissions(Request $request)
    {
        Log::info("copyUserPermissions", $request->all());
        $validated = $request->validate([
            'source_user_id' => 'required|integer',
            'target_user_id' => 'required|integer',
        ]);

        $this->permissionService->copyUserPermissions(
            $validated['source_user_id'],
            $validated['target_user_id']
        );

        return back()->with('success', 'Permissions copied successfully.');
    }

}
