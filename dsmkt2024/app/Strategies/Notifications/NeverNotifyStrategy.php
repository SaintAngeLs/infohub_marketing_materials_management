<?php

namespace App\Strategies\Notifications;

class NeverNotifyStrategy implements NotificationStrategy
{
    public function notify($user, $message): void
    {
        // not implemented exception
    }
}
