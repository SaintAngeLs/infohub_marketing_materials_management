<?php

namespace App\Http\Controllers\Admin\Users;

use App\Contracts\IUserService;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UsersGroup;
use App\Services\UserService;
use App\Strategies\UserCreation\CreateUserWithoutPassword;
use App\Strategies\UserCreation\CreateUserWithPassword;
use App\Strategies\UserCreation\UserCreationStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(Request $request)
    {
        Log::info('UserManagementController', $request->all());

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'users_groups_id' => 'required|exists:users_groups,id',
            'address' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:12',
        ]);


        // $this->userService->createUser($request->all());
        $strategy = $this->getStrategy($request);
        $user = $strategy->createUser($validatedData);

        $groupPermissions = UsersGroup::find($validatedData['users_groups_id'])->menuItems;
        $user->accessibleMenuItems()->sync($groupPermissions->pluck('id'));

        return redirect()->route('menu.users.index')->with('success', 'User created successfully.');
    }

    public function update(Request $request, $userId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'users_groups_id' => 'required|exists:users_groups,id',
            'address' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:12',
        ]);

        try {
            $user = $this->userService->updateUser($userId, $validatedData);
            $groupPermissions = UsersGroup::find($validatedData['users_groups_id'])->menuItems;
            $user->accessibleMenuItems()->sync($groupPermissions->pluck('id'));
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    protected function getStrategy(Request $request): UserCreationStrategy
    {
        Log::info('getStrrategy request', $request->all());
        if ($request->input('password_option') === 'yes') {
            return new CreateUserWithPassword();
        }

        return new CreateUserWithoutPassword();
    }
}
