<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Fuerza a Laravel a generar URLs con el dominio definido en APP_URL
        if (config('app.url')) {
            URL::forceRootUrl(config('app.url'));
        }

        // Si tu sitio usa HTTPS (Hostinger, Cloudflare, etc.)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
