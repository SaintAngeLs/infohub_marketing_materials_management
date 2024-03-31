<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\IPermissionService;
use Illuminate\Support\Facades\Log;

class PermissionManagementController extends Controller
{
    protected $permissionService;

    public function __construct(IPermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
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


        return response()->json(['message' => 'User permissions updated successfully.']);
    }
}
