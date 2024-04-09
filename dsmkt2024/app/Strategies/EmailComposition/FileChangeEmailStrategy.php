<?php

namespace App\Strategies\EmailComposition;

use App\Mail\FileChangedNotification;
use Illuminate\Support\Facades\Mail;

class FileChangeEmailStrategy implements EmailCompositionStrategy
{
    public function compose($user, $content)
    {
        try {
            Mail::to($user->email)->send(new FileChangedNotification($user, $content));
            return true;
        } catch (\Exception $e) {
            return false; 
        }
    }
}
