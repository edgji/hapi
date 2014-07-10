<?php namespace Edgji\Hapi\Facades;

use Dingo\Api\Facades\API as Facade;

class API extends Facade {

    /**
     * @param string $model
     * @param array $options
     * @return mixed
     */
    public static function resource($model, array $options = array())
    {
        return static::$app['router']->apiResource($model, $options);
    }

    /**
     * @param string $model
     * @param string $parent
     * @param array $options
     * @return mixed
     */
    public static function nestedResource($model, $parent = false, array $options = array())
    {
        if ($parent)
        {
            $options['parent'] = $parent;
        }

        return static::$app['router']->apiResource($model, $options);
    }
}