<?php namespace Edgji\Hapi\Routing;

use Dingo\Api\Routing\Router as BaseRouter;

class Router extends BaseRouter {

    protected $controller;

    /**
     * The entity parameter patterns.
     *
     * @var array
     */
    protected $entityPatterns = array();

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * Route a model to an api controller.
     *
     * @param  string  $model
     * @param  array   $options
     * @return void
     */
    public function entityResource($model, array $options = array())
    {
        $parent = array_pull($options, 'parent', false);
        $name = array_pull($options, 'name', false);

        if ( ! $name)
        {
            if ($parent) {
                $name = '{'.$parent.'}/{id}/{'.$model.'}';
                $this->entityPattern($parent);
            }
            else
            {
                $name = '{'.$model.'}';
            }
            $this->entityPattern($model);
        }

        //$routeKey = $this->routes->count();

        $controller = array_pull($options, 'controller', $this->controller);
        $this->resource($name, $controller, $options);

        //$routes = $this->routes->getRoutes();
        //$count = count($routes);
        //
        //while($routeKey < $count)
        //{
        //    $route = $routes[$routeKey++];
        //    $route->where($this->entityPatterns);
        //}
    }

    protected function entityPattern($key)
    {
        $entity = str_plural($key);
        $this->entityPatterns[$key] = "({$entity})";
    }
}