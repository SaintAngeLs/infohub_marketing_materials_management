<?php

namespace App\Providers;

use App\Contracts\IMenuItemTreeElementService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\IMenuItemService;
use App\Services\MenuItemService;

class MenuItemTreeElementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(IMenuItemTreeElementService::class, MenuItemTreeElementServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Boot method...
    }
}
