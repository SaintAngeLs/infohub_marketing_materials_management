<?php

namespace App\Http\Controllers\Admin\Users;

use App\Contracts\IUserService;
use App\Http\Controllers\Controller;
use App\Models\UsersGroup;
use Illuminate\Http\Request; 

class UserViewController extends Controller
{
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $sortField = $request->query('sort', 'name'); // Default sort by name
        $sortOrder = $request->query('order', 'asc'); // Default order

        // Assuming $this->userService->getAllUsers() can be modified or replaced to accept sorting parameters
        $users = $this->userService->getAllUsersSorted($sortField, $sortOrder);
        $userGroups = UsersGroup::all();

        return view('admin.users.index', compact('users', 'userGroups'));
    }
    public function create()
    {
        $userGroups = UsersGroup::all();
        return view('admin.users.create', compact('userGroups'));
    }
    public function edit($userId)
    {
        $editUserDTO = $this->userService->getEditUserDTO($userId);
        return view('admin.users.edit', ['user' => $editUserDTO->user, 'userGroups' => $editUserDTO->userGroups]);
    }

}
