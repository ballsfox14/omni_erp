<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        // Solo compartir la configuración si la tabla existe
        if (\Illuminate\Support\Facades\Schema::hasTable('empresa_configs')) {
            View::composer('*', function ($view) {
                $config = \App\Models\EmpresaConfig::getConfig();
                $view->with('empresaConfig', $config);
            });
        }
    }
}