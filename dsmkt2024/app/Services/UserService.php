<?php

namespace App\Services;

use App\Contracts\IUserService;
use App\Models\User;
use App\Models\UsersGroup;

class UserService implements IUserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function createUser(array $userData)
    {
        $userData['password'] = bcrypt($userData['password']);
        return User::create($userData);
    }

    public function getEditUserDTO($userId)
    {
        $user = User::findOrFail($userId);
        $userGroups = UsersGroup::all();
        return (object)['user' => $user, 'userGroups' => $userGroups];
    }

    public function updateUser($userId, array $userData)
    {
        $user = User::findOrFail($userId);
        $user->update($userData);
        return $user;
    }

    public function getUserById($userId)
    {
        return User::findOrFail($userId);
    }
}
