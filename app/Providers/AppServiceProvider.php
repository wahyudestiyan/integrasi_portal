<?php

namespace App\Providers;
use Carbon\Carbon;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
 public function boot(): void
{
    Carbon::setLocale('id'); // Bahasa Indonesia

    if (app()->environment('production')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
}
