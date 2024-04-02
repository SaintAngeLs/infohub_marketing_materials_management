<?php

namespace App\Services;

use App\Contracts\IEmailService;
use Illuminate\Support\Facades\Mail;
use App\Models\UserEmail; // Assuming UserEmail is your model for the 'user_emails' table

class EmailService implements IEmailService
{
    public function sendEmail($to, $content, $userId): bool
    {
        try {
            Mail::raw($content, function ($message) use ($to) {
                $message->to($to)->subject('Notification Email');
            });

            UserEmail::create([
                'user_id' => $userId,
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
