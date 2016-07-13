<?php

namespace Clumsy\AgeCheck;

use Illuminate\Support\ServiceProvider;

class AgeCheckServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('clumsy/age-check.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AgeCheck::class, function ($app) {
            return new AgeCheck();
        });
        
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'clumsy.age-check');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(AgeCheck::class);
    }
}
