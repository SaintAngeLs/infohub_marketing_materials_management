<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\IFileService;
use App\Services\FileService;

class FileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(IFileService::class, FileService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Boot method...
    }
}
