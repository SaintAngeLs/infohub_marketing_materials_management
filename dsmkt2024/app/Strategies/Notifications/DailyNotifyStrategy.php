<?php

namespace App\Strategies\Notifications;

use App\Contracts\IEmailService;
use App\Models\User;

class DailyNotifyStrategy implements NotificationStrategy
{
    protected $emailService;

    public function __construct(IEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function notify(User $user, $message): void
    {
        // Your logic to decide if it's the right time to send a daily notification
        $this->emailService->sendEmail($user->email, $message);
    }
}
