<?php

namespace App\Listeners;

use App\Models\UserAuthentication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;

class LogUserLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        UserAuthentication::create([
            'user_id' => $event->user->id,
            'ip' => request()->ip(),
            'fingerprint' => now(),
        ]);
    }
}
