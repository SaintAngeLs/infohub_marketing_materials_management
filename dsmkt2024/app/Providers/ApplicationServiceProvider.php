<?php

namespace App\Providers;

use App\Contracts\IApplication;
use App\Services\ApplicationService;
use Illuminate\Support\ServiceProvider;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Services:
     */
    public function register(): void
    {
        $this->app->bind(IApplication::class, ApplicationService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
