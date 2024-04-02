<?php

namespace App\Strategies\Notifications;

interface NotificationStrategy
{
    public function notify($user, $message): void;
}
