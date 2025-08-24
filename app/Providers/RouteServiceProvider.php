<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    protected $home = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        // Asegúrate de llamar esta línea
        $this->configureRateLimiting();

        // ...existing code...
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Páginas públicas (GET)
        RateLimiter::for('public-pages', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        // Envíos de formularios (POST)
        RateLimiter::for('form-submissions', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // Mantén el limiter 'api' si lo usas
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}