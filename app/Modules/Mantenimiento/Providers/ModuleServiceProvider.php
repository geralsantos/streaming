<?php

namespace App\Modules\Mantenimiento\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(module_path('mantenimiento', 'Resources/Lang', 'app'), 'mantenimiento');
        $this->loadViewsFrom(module_path('mantenimiento', 'Resources/Views', 'app'), 'mantenimiento');
        $this->loadMigrationsFrom(module_path('mantenimiento', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('mantenimiento', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('mantenimiento', 'Database/Factories', 'app'));
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
