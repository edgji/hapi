<?php namespace Edgji\Hapi;

use Edgji\Hapi\Routing\Router;
use Dingo\Api\ApiServiceProvider as BaseServiceProvider;

class HapiServiceProvider extends BaseServiceProvider {

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        // make sure we're still booting the dingo/api package
        parent::boot();

        $this->package('edgji/hapi', 'hapi', __DIR__);
    }

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

            $router->setController($this->app['config']['hapi::controller']);
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
