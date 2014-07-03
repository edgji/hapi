<?php namespace Edgji\Hapi\Routing;

use Dingo\Api\Routing\Controller as BaseController;

class Controller extends BaseController {

    /**
     * Display a listing of {Model}
     *
     * @return Response
     */
    public function index()
    {
        return 'test';
    }
}