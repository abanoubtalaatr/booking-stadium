<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('pagination::default'); // Optional: Set default pagination view
        Paginator::defaultSimpleView('pagination::simple-default'); // Optional: Set default simple pagination view
        Paginator::useBootstrap(); // Use Bootstrap styles for pagination (if needed)
    }
}
