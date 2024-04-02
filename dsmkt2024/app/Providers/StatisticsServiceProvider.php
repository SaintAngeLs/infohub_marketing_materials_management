<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\IStatistics;
use App\Services\StatisticsService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IStatistics::class, StatisticsService::class);
    }
    public function boot(): void
    {
        // Boot method...
    }
}
