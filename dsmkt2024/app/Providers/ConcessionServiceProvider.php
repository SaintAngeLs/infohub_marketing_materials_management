<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\IConcessionService;
use App\Services\ConcessionService;

class ConcessionServiceProvider extends ServiceProvider
{
     /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(IConcessionService::class, ConcessionService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
