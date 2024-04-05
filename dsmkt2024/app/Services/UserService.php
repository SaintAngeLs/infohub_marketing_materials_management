<?php

namespace App\Services;

use App\Contracts\IEmailService;
use App\Contracts\IUserService;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UsersGroup;
use App\Strategies\Notifications\DailyNotifyStrategy;
use App\Strategies\Notifications\EveryChangeNotifyStrategy;
use App\Strategies\Notifications\NeverNotifyStrategy;
use App\Strategies\Notifications\NotificationStrategy;

class UserService implements IUserService
{
    protected $emailService;

    public function __construct(IEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

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
            case 0: // Nigdy
                return new NeverNotifyStrategy();
            case 1: // Raz dziennie
                return new DailyNotifyStrategy($this->emailService);
            case 2: // Przy każdej zmianie
                return new EveryChangeNotifyStrategy($this->emailService);
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

    public function notifyUserAboutFileChange($menuItemId, $message)
    {
        $userNotifications = UserNotification::where('menu_item_id', $menuItemId)
                                             ->with('user') // Eager load user relationship
                                             ->get();

        foreach ($userNotifications as $notification) {
            // Use the frequency from the notification to determine the strategy
            $strategy = $this->getNotificationStrategy($notification->frequency);
            if ($strategy) {
                $strategy->notify($notification->user, $message);
            } else {
                \Log::warning("Unsupported notification frequency: {$notification->frequency}");
            }
        }
    }
}
