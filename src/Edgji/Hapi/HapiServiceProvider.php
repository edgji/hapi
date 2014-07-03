<?php namespace Edgji\Hapi;

use Edgji\Hapi\Routing\Router;
use Dingo\Api\ApiServiceProvider as BaseServiceProvider;

class HapiServiceProvider extends BaseServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        parent::register();
        $this->setDefaultController();
	}

    protected function setDefaultController()
    {
        $this->app->booting(function ($app) {
            $router = $app['router'];

            $router->setController('Hapi\\Routing\\Controller');
        });
    }

    protected function registerRouter()
    {
        $this->app['router'] = $this->app->share(function ($app) {
            $router = new Router($app['events'], $app);

            if ($app['env'] == 'testing') {
                $router->disableFilters();
            }

            return $router;
        });
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
