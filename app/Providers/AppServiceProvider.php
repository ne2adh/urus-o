<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

use App\Services\ParticipanteServiceInterface;
use App\Services\ParticipanteService;

use App\Repositories\ParticipanteRepositoryInterface;
use App\Repositories\EloquentParticipanteRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ParticipanteRepositoryInterface::class,
            EloquentParticipanteRepository::class
        );

        $this->app->bind(
            ParticipanteServiceInterface::class,
            ParticipanteService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Evita el error de key length en MySQL antiguas
        Schema::defaultStringLength(191);
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        // 1) Cargamos explícitamente las rutas web (routes/web.php)
        Route::middleware('web')
            // ->namespace($this->app->getNamespace().'Http\Controllers')
            ->group(base_path('routes/web.php'));

        // 2) Cargamos explícitamente las rutas API (routes/api.php)
        Route::prefix('api')
            ->middleware('api')
            // ->namespace($this->app->getNamespace().'Http\Controllers')
            ->group(base_path('routes/api.php'));
    }
}
