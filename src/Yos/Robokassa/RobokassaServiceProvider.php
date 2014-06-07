<?php namespace Yos\Robokassa;

use Illuminate\Support\ServiceProvider;

class RobokassaServiceProvider extends ServiceProvider {

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
		$this->package('yos/robokassa');

        include __DIR__.'/../../config/credentials.php';
        include __DIR__ . '/../../config/config.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('Yos\Robokassa\Merchant', function(){
            return new Merchant([
                'login'     => Config::get('robokassa::login'),//'robo-yos-test'
                'password1' => Config::get('robokassa::password1'), //'35bac453915'
                'password2' => Config::get('robokassa::password2'), //'45bac453915'
            ], Config::get('robokassa::testmode'));
        });

        $this->app->bind('Yos\Robokassa\Payment', function($app){
            return new Payment($app->make('Yos\Robokassa\Merchant'));
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
