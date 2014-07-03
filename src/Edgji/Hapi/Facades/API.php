<?php namespace Edgji\Hapi\Facades;

use Dingo\Api\Facades\API as Facade;

class API extends Facade {

    /**
     * @param string $model
     * @param array $options
     * @return mixed
     */
    public static function resource($model, Array $options = array())
    {
        return static::$app['router']->entityResource($model, $options);
    }
}