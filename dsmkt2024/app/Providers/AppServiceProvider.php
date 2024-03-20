<?php

namespace App\Providers;

use App\Http\CustomKernel;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Services:
     *  -   CustomKernel with middleware
     */
    public function register(): void
    {
        $this->app->singleton(Kernel::class, CustomKernel::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
