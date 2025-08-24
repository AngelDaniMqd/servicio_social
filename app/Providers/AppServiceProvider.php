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
        // 🔒 Forzar HTTPS si está configurado
        if (config('app.force_https', false)) {
            URL::forceScheme('https');
        }
        
        // 🔒 Detectar HTTPS del servidor
        if (request()->server('HTTPS') === 'on' || 
            request()->server('SERVER_PORT') == 8443 || 
            request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        if (app()->environment('production') || env('APP_FORCE_HTTPS')) {
            URL::forceScheme('https');
        }
    }
}
