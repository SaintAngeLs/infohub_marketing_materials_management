<?php

namespace App\Services;

use App\Contracts\IEmailService;
use Illuminate\Support\Facades\Mail;

class EmailService implements IEmailService
{
    public function sendEmail($to, $content): bool
    {
        try {
            Mail::raw($content, function ($message) use ($to) {
                $message->to($to)
                        ->subject('Notification Email');
            });
            UserEmail::create([
                'user_id' => /* You need to determine and provide the user ID */,
                'address' => $to,
                'content' => $content,
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
