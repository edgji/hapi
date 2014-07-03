<?php namespace Edgji\Hapi\Routing;

use Dingo\Api\Routing\Router as BaseRouter;

class Router extends BaseRouter {

    protected $controller;

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
    public function entityResource($model, Array $options = array())
    {
        $parent = isset($options['parent']) ? str_plural($options['parent']) : false;
        unset($options['parent']);

        if (isset($options['name']))
        {
            $name = $options['name'];
            unset($options['name']);
        }
        else
        {
            $name = ($parent) ? $parent. '/{:id}/' .str_plural($model) : str_plural($model);
        }

        $controller = isset($options['controller']) ? $options['controller'] : $this->controller;
        unset($options['controller']);

        $this->resource($name, $controller, $options);
    }
}