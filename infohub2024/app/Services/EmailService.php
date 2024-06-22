<?php

namespace App\Services;

use App\Contracts\IEmailService;
use App\Models\User;
use App\Strategies\EmailComposition\EmailCompositionStrategy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\UserEmail;

class EmailService implements IEmailService
{
    public function sendEmail($to, $content, $userId, EmailCompositionStrategy $strategy): bool
    {
        try {
            $user = User::find($userId);

            if (!$user) {
                return false;
            }

            $emailSent = $strategy->compose($user, $content);

            $status = $emailSent ? 'sent' : 'failed';

            UserEmail::create([
                'user_id' => $userId,
                'address' => $to,
                'content' => $content,
                'status' => $status,
                'sent_at' => now(),
            ]);

            return $emailSent;
        } catch (\Exception $e) {
            Log::error("EmailService: Exception occurred - " . $e->getMessage(), ['exception' => $e]);

            try {
                UserEmail::create([
                    'user_id' => $userId,
                    'address' => $to,
                    'content' => $content,
                    'status' => 'failed',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $dbException) {
                Log::critical("EmailService: Could not log email status to database - " . $dbException->getMessage(), ['exception' => $dbException]);
            }
            return false;
        }
    }
}
