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
            __DIR__.'/../../config/config.php' => config_path('clumsy/age-check.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/Resources/Views' => base_path('resources/views/vendor/clumsy/age-check'),
        ], 'views');

        $this->loadViewsFrom(__DIR__.'/Resources/Views', 'clumsy-age-check');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('agecheck', function () {
            return new AgeCheck();
        });
        
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'clumsy.age-check');

        $this->registerRoutes();
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

    public function registerRoutes()
    {
        $this->app['router']->group([
                'prefix'     => 'age-check',
                'middleware' => 'web'
            ], function () {

                $this->app['router']->match(['GET'], '/', [
                    'as'   => 'age-check.validate',
                    'uses' => 'Clumsy\AgeCheck\Http\Controllers\AgeCheckController@validate'
                ]);

                $this->app['router']->match(['POST'], '/', [
                    'as'   => 'age-check.validateForm',
                    'uses' => 'Clumsy\AgeCheck\Http\Controllers\AgeCheckController@validateForm'
                ]);
            }
        );
    }
}
