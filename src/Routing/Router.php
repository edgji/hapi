<?php namespace Edgji\Hapi\Routing;

use Dingo\Api\Routing\Router as BaseRouter;

class Router extends BaseRouter {

    /**
     * Indicates whether paths are snake cased.
     *
     * @var bool
     */
    public static $snakePaths = true;

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
    public function apiResource($model, array $options = array())
    {
        $parent = array_pull($options, 'parent', false);
        $name = array_pull($options, 'name', false);
        $controller = array_pull($options, 'controller', $this->controller);

        if ( ! $name)
        {
            $model = (static::$snakePaths) ? snake_case(str_plural($model), '-') : str_plural(strtolower($model));

            // nest resource routes if parent is present
            if ($parent)
            {
                $parent = (static::$snakePaths) ? snake_case(str_plural($parent), '-') : str_plural(strtolower($parent));

                $only = array_pull($options, 'only', false);
                $except = array_pull($options, 'except', []);

                $addIndexAction = (false === $only || in_array('index', $only)) && ! in_array('index', $except);

                // special case resource index method needs to be handled separately here
                if ($addIndexAction)
                {
                    $name = $parent . '.' . $model;
                    $base = $model;

                    $this->addResourceIndex($name, $base, $controller, $options);
                    // make sure we skip the index action when we call the resource method
                    $options['except'] =  array_merge($except, ['index']);
                }

                // set prefixed name for all other resource actions
                $name = $parent . '/{'.$parent.'}/' . $model;
            }
            else
            {
                $name = $model;
            }
        }

        $this->resource($name, $controller, $options);
    }
}