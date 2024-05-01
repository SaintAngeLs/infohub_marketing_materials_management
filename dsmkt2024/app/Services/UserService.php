<?php

namespace App\Services;

use App\Contracts\IEmailService;
use App\Contracts\IUserService;
use App\Models\File;
use App\Models\MenuItems\MenuItem;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UsersGroup;
use App\Strategies\Notifications\DailyNotifyStrategy;
use App\Strategies\Notifications\EveryChangeNotifyStrategy;
use App\Strategies\Notifications\NeverNotifyStrategy;
use App\Strategies\Notifications\NotificationStrategy;
use Illuminate\Support\Facades\Log;

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
        Log::info('User Service request is:', $userData);
        $user = User::findOrFail($userId);
        $user->update($userData);

        $user->name = $userData['name'];
        $user->surname = $userData['surname'];
        $user->email = $userData['email'];
        $user->phone = $userData['phone'];
        $user->users_groups_id = $userData['users_groups_id'];

        $user->active = $userData['status'] === '1' ? 1 : 0;

        $user->save();
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
                                                ->with('user')
                                                ->get();

        $menuItem = MenuItem::find($menuItemId);
        $menuItemName = $menuItem->name;

        $changedFiles = File::where('menu_id', $menuItemId)
                            ->where('updated_at', '>', now()->subDay())
                            ->get();

        $fileList = $changedFiles->map(function ($file) {
            return "- " . $file->name . ' (zmiany o ' . $file->updated_at->format('Y-m-d H:i:s') . ')';
        })->implode("\n");

        // Modify the message to include menu item details
        $detailedMessage = "{$message}\n\nElement menu: {$menuItemName}\nZmienione pliki:\n{$fileList}";

        foreach ($userNotifications as $notification) {
            $strategy = $this->getNotificationStrategy($notification->frequency);
            if ($strategy) {
                $strategy->notify($notification->user, $detailedMessage);
            } else {
                Log::warning("Unsupported notification frequency: {$notification->frequency}");
            }
        }
    }



    public function getAllUsersSorted($sortField = 'name', $sortOrder = 'asc')
    {
        if ($sortField === 'group') {
            return User::select('users.*')
                ->leftJoin('users_groups', 'users.users_groups_id', '=', 'users_groups.id')
                ->orderBy('users_groups.name', $sortOrder)
                ->orderBy('users.name', $sortOrder)
                ->get();
        } else {

            return User::orderBy($sortField, $sortOrder)->get();
        }
    }
}
