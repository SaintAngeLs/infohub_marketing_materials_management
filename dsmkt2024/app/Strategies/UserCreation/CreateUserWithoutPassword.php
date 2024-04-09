<?php

namespace App\Strategies\UserCreation;

use App\Notifications\UserPasswordSetupNotification;
use App\Strategies\UserCreation\UserCreationStrategy;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateUserWithoutPassword implements UserCreationStrategy
{
    public function createUser(array $userData)
    {
        Log::Info("CreateUserWithouPassword", $userData);
        unset($userData['password']);
        $user = User::create($userData);

        // Send notification
        $user->notify(new UserPasswordSetupNotification($user));
        Log::info("Notification sent to user without password", ['user_id' => $user->id]);

        return $user;
    }
}
