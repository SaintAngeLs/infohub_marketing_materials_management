<?php

namespace App\Strategies\EmailComposition;

interface EmailCompositionStrategy
{
    public function compose($user, $content);
}
