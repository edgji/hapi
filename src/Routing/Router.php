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
    public function entityResource($model, array $options = array())
    {
        $parent = array_pull($options, 'parent', false);
        $name = array_pull($options, 'name', false);

        if ( ! $name)
        {
            $name = ($parent) ? str_plural($parent).'/{id}/'.str_plural($model) : str_plural($model);
        }

        $controller = array_pull($options, 'controller', $this->controller);
        $this->resource($name, $controller, $options);
    }
}