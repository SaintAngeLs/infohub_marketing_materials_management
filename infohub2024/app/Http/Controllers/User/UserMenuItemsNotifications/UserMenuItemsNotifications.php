<?php

use App\Models\UserNotification;
use App\Contracts\IEmailService;
use App\Http\Controllers\Controller;
use App\Services\EmailService;
use App\Strategies\Notifications\DailyNotifyStrategy;
use App\Strategies\Notifications\EveryChangeNotifyStrategy;
use App\Strategies\Notifications\NeverNotifyStrategy;

class UserMenuItemsNotificationsController extends Controller
{
    private $emailService;
    public function __construct(IEmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    public function notifyUsers(Request $request)
    {
        $userId = $request->input('user_id', Auth::id());
        $userNotifications = UserNotification::where('user_id', $userId)->get();

        foreach ($userNotifications as $notification) {
            $user = $notification->user;
            $strategy = $this->getNotificationStrategy($notification->frequency);
            $strategy->notify($user, "Your custom message here.");
        }
    }
    protected function getNotificationStrategy($frequency)
    {
        switch ($frequency) {
            case 1:
                return new DailyNotifyStrategy($this->emailService);
            case 2:
                return new EveryChangeNotifyStrategy($this->emailService);
            default:
                return new NeverNotifyStrategy();
        }
    }
}
