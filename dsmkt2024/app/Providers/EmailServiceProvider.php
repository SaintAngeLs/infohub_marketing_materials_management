<?php

namespace App\Providers;

use App\Contracts\IEmailService;
use App\Contracts\IUserService;
use App\Services\EmailService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
     /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(IEmailService::class, EmailService::class);
        $this->app->bind(IUserService::class, UserService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
