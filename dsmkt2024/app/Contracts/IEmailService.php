<?php

namespace App\Contracts;

interface IEmailService
{
    public function sendEmail($to, $content): bool;
}
