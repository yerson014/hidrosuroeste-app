<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Asegúrate de que esta línea esté presente

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
        // Fuerza a Laravel a generar todos los enlaces de formularios con HTTPS en producción
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}