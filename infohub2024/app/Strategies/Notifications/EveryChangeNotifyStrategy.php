<?php

namespace App\Strategies\Notifications;

use App\Contracts\IEmailService;
use App\Models\User;
use App\Strategies\EmailComposition\FileChangeEmailStrategy;

class EveryChangeNotifyStrategy implements NotificationStrategy
{
    protected $emailService;
    public function __construct(IEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function notify(User $user, $message): void
    {
        $emailCompositionStrategy = new FileChangeEmailStrategy();
        $this->emailService->sendEmail($user->email, $message, $user->id, $emailCompositionStrategy);
    }
}
