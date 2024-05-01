<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\IUserService;
use App\Services\UserService;

class UserServiceProvider extends ServiceProvider
{
     /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(IUserService::class, UserService::class);
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Boot method...
    }
}
