<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TelegramNotificationService;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TelegramNotificationService::class, function ($app) {
            return new TelegramNotificationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
