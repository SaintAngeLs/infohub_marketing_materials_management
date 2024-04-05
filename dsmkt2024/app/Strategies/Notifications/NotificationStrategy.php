<?php

namespace App\Strategies\Notifications;

use App\Models\User;

interface NotificationStrategy
{
    public function notify(User $user, $message): void;
}
