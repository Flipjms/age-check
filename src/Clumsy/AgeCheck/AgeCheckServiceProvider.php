<?php namespace Clumsy\AgeCheck;

use Illuminate\Support\ServiceProvider;

class AgeCheckServiceProvider extends ServiceProvider {

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
		$this->package('clumsy/age-check');

        $this->app['agecheck'] = $this->app->share(function($app){
										return new AgeCheck;
								  });

        // Create an Alias
		// $this->app->booting(function()
		// {
		// 	$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		// 	$loader->alias('AgeCheck', 'Clumsy\AgeCheck\Facade');
		// });
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
