<?php

namespace App\Contracts;
use App\Strategies\EmailComposition\EmailCompositionStrategy;

interface IEmailService
{
    public function sendEmail($to, $content, $userId, EmailCompositionStrategy $strategy): bool;
}
