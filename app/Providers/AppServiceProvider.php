<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        // Set locale based on user preference or session
        if (Auth::check() && Auth::user()->language_preference) {
            app()->setLocale(Auth::user()->language_preference);
        } elseif (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            app()->setLocale(config('app.locale'));
        }
    }
}
