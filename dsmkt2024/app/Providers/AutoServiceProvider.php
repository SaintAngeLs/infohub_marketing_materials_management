<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\IAutoService;
use App\Services\AutoService;

class AutoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IAutoService::class, AutoService::class);
    }

    public function boot(): void
    {
        //
    }
}
