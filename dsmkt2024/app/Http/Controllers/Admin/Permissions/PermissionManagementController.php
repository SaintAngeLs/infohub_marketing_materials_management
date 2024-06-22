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
        Log::info('Received request data:', $request->all());

        try {
            $validated = $request->validate([
                'group_id' => 'required|integer|exists:users_groups,id',
                'permissions' => 'required|array',
                'permissions.*' => 'integer|exists:menu_items,id',
            ]);

            Log::info('Validated data:', $validated);

            $this->permissionService->updateGroupPermissions(
                $validated['group_id'],
                $validated['permissions']
            );

            $this->statisticsService->logUserActivity(auth()->id(), [
                'uri' => $request->path(),
                'post_string' => json_encode($request->except('_token')),
                'query_string' => $request->getQueryString(),
            ]);

            $response = response()->json(['message' => 'Group permissions updated successfully.']);
            Log::info('Response:', ['content' => $response->getContent()]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Error in updateGroupPermission:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function updateUserPermission(Request $request)
    {
        Log::info('Received request data:', $request->all());

        try {
            $validated = $request->validate([
                'menu_id' => 'required|integer',
                'user_id' => 'required|integer',
                'action' => 'required|in:assign,remove',
            ]);

            Log::info('Validated data:', $validated);

            $this->permissionService->updateUserPermission(
                $validated['menu_id'],
                $validated['user_id'],
                $validated['action']
            );

            $this->statisticsService->logUserActivity(auth()->id(), [
                'uri' => $request->path(),
                'post_string' => json_encode($request->except('_token')),
                'query_string' => $request->getQueryString(),
            ]);

            $response = response()->json(['message' => 'User permissions updated successfully.']);
            Log::info('Response:', ['content' => $response->getContent()]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Error in updateUserPermission:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function copyUserPermissions(Request $request)
    {
        Log::info("Received request data:", $request->all());

        try {
            $validated = $request->validate([
                'source_user_id' => 'required|integer',
                'target_user_id' => 'required|integer',
            ]);

            Log::info('Validated data:', $validated);

            $this->permissionService->copyUserPermissions(
                $validated['source_user_id'],
                $validated['target_user_id']
            );

            $response = back()->with('success', 'Permissions copied successfully.');
            Log::info('Response:', ['content' => $response]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Error in copyUserPermissions:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => 'An error occurred']);
        }
    }
}
