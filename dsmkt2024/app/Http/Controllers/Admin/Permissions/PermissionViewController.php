<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UsersGroup;

class PermissionViewController extends Controller
{
    public function index()
    {
        return view('admin.users.permissions');
    }

    public function editUserPermissions($userId = null)
    {
        $user = null;
        $isEdit = false;

        if ($userId !== null) {
            $user = User::find($userId);
            $isEdit = true;
        }

        return view('admin.users.edit-permissions', compact('user', 'isEdit'));
    }

    public function editGroupPermissions($groupId = null)
    {
        $group = null;
        $isEdit = false;

        if ($groupId !== null) {
            $group = UsersGroup::where('id', $groupId)->with('permissions')->first();
            $isEdit = true;

            if (!$group) {
                return redirect()->route('admin.groups.index')->with('error', 'Group not found.');
            }
        }
        return view('admin.groups.edit-permissions', compact('group', 'isEdit'));
    }

}
