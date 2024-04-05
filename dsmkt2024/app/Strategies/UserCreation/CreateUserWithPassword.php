<?php

namespace App\Strategies\UserCreation;

use App\Strategies\UserCreation\UserCreationStrategy;
use App\Models\User;

class CreateUserWithPassword implements UserCreationStrategy
{
    public function createUser(array $userData)
    {
        $userData['password'] = bcrypt($userData['password']);
        return User::create($userData);
    }
}
