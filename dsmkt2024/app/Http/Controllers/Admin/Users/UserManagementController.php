<?php

namespace App\Http\Controllers\Admin\Users;

use App\Contracts\IStatistics;
use App\Contracts\IUserService;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UsersGroup;
use App\Services\UserService;
use App\Strategies\UserCreation\CreateUserWithoutPassword;
use App\Strategies\UserCreation\CreateUserWithPassword;
use App\Strategies\UserCreation\UserCreationStrategy;
// use Dotenv\Exception\ValidationException;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    protected $userService;
    protected $statisticsService;

    public function __construct(IUserService $userService, IStatistics $statisticsService)
    {
        $this->userService = $userService;
        $this->statisticsService = $statisticsService;
    }

    public function store(Request $request)
    {
        Log::info('UserManagementController', $request->all());

        $rules = [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'users_groups_id' => 'required|exists:users_groups,id',
            'address' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:12',
        ];

        if ($request->input('password_option') === 'yes') {
            $rules['password'] = 'nullable|string|min:8|confirmed';
            $rules['address'] = 'nullable|string|max:255';
            $rules['code'] = 'nullable|string|max:10';
            $rules['city'] = 'nullable|string|max:100';
        }

        $validator = Validator::make($request->all(), $rules);

        try {
            $validatedData = $validator->validated();
            Log::info('UserManagementController Validated Data', $validatedData);
        } catch (ValidationException $e) {
            Log::error('Validation Error', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            throw $e;
        }


        $strategy = $this->getStrategy($request);
        $user = $strategy->createUser($validatedData);

        $groupPermissions = UsersGroup::find($validatedData['users_groups_id'])->menuItems;
        $user->accessibleMenuItems()->sync($groupPermissions->pluck('id'));

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

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
            $this->statisticsService->logUserActivity(auth()->id(), [
                'uri' => $request->path(),
                'post_string' => $request->except('_token'),
                'query_string' => $request->getQueryString(),
            ]);
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
