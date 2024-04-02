<?php

namespace App\Services;

use App\Contracts\IUserService;
use App\Models\User;
use App\Models\UsersGroup;
use App\Strategies\Notifications\DailyNotifyStrategy;
use App\Strategies\Notifications\EveryChangeNotifyStrategy;
use App\Strategies\Notifications\NeverNotifyStrategy;
use App\Strategies\Notifications\NotificationStrategy;

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
    protected function getNotificationStrategy($preference): NotificationStrategy
    {
        switch ($preference) {
            case 'never':
                return new NeverNotifyStrategy();
            case 'daily':
                return new DailyNotifyStrategy();
            case 'every_change':
                return new EveryChangeNotifyStrategy();
            default:
                return new NeverNotifyStrategy();
        }
    }

    public function sendNotification($userId, $message)
    {
        $user = $this->getUserById($userId);
        $strategy = $this->getNotificationStrategy($user->notification_preference);
        $strategy->notify($user, $message);
    }
}
