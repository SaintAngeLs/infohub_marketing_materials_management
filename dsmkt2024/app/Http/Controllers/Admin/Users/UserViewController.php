<?php

namespace App\Http\Controllers\Admin\Users;

use App\Contracts\IUserService;
use App\Http\Controllers\Controller;
use App\Models\UsersGroup;

class UserViewController extends Controller
{
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
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
