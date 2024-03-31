<?php

namespace App\Strategies\UserCreation;

interface UserCreationStrategy
{
    public function createUser(array $userData);
}
