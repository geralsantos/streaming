<?php

namespace App\Modules\Streaming\Providers;

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
        $this->loadTranslationsFrom(module_path('streaming', 'Resources/Lang', 'app'), 'streaming');
        $this->loadViewsFrom(module_path('streaming', 'Resources/Views', 'app'), 'streaming');
        $this->loadMigrationsFrom(module_path('streaming', 'Database/Migrations', 'app'));
        if(!$this->app->configurationIsCached()) {
            $this->loadConfigsFrom(module_path('streaming', 'Config', 'app'));
        }
        $this->loadFactoriesFrom(module_path('streaming', 'Database/Factories', 'app'));
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
