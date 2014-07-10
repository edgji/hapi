<?php namespace Edgji\Hapi\Routing;

use Dingo\Api\Routing\Router as BaseRouter;

class Router extends BaseRouter {

    /**
     * Indicates whether paths are snake cased.
     *
     * @var bool
     */
    protected static $snakePaths = true;

    protected static $snakeDelimiter = '-';

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
            // nest resource routes if parent is present
            if ($parent)
            {
                // conventionally we should avoid being repetitive
                // nested model routes should not reference parents
                if (strpos($model, $parent) === 0)
                    $model = substr($model, strlen($parent));

                $model = static::prepareEntityBaseName($model);
                $parent = static::prepareEntityBaseName($parent);

                $only = array_pull($options, 'only', false);
                $except = array_pull($options, 'except', []);

                $addIndexAction = (false === $only || in_array('index', $only)) && ! in_array('index', $except);

                // special case resource index method needs to be handled separately here
                if ($addIndexAction)
                {
                    $name = $parent . '.' . $model;

                    $this->addResourceIndex($name, $model, $controller, $options);
                    // make sure we skip the index action when we call the resource method
                    $options['except'] =  array_merge($except, ['index']);
                }

                // set prefixed name for all other resource actions
                $name = $parent . '/{'.$parent.'}/' . $model;
            }
            else
            {
                $model = static::prepareEntityBaseName($model);
                $name = $model;
            }
        }

        // hand off to default resource router
        $this->resource($name, $controller, $options);
    }

    /**
     * Resolve a model name route based on simple convention.
     *
     * @param  string  $entity
     * @return string
     */
    protected static function prepareEntityBaseName($entity)
    {
        return (static::$snakePaths) ? snake_case(str_plural($entity), static::$snakeDelimiter) : str_plural(strtolower($entity));
    }
}