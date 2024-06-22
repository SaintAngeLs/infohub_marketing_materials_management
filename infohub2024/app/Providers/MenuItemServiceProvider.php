<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\IMenuItemService;
use App\Services\MenuItemService;

class MenuItemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(IMenuItemService::class, MenuItemService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Boot method...
    }
}
