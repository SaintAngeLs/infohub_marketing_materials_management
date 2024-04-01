<?php

namespace App\Providers;

use App\Http\CustomKernel;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        View::composer('partials.user_menu', function ($view) {
            \Log::debug('User menu view composer is running.');
            if (Auth::check()) {
                $user = Auth::user();
                $menuItems = $user->accessibleMenuItems()->get();
                \Log::debug('menuItems', $menuItems->toArray());
                $view->with('menuItems', $menuItems);
            }
        });

    }
}
