<?php namespace Edgji\Hapi\Routing;

use Dingo\Api\Dispatcher;
use Dingo\Api\Auth\Shield;
use Dingo\Api\Http\ResponseBuilder;
use Dingo\Api\Routing\Controller as BaseController;

use Illuminate\Support\Facades\App;

class Controller extends BaseController {

    protected $model;
    protected $parent;

    /**
     * Create a new controller instance.
     *
     * @param  \Dingo\Api\Dispatcher  $api
     * @param  \Dingo\Api\Auth\Shield  $auth
     * @param  \Dingo\Api\Http\ResponseBuilder  $response
     * @return void
     */
    public function __construct(Dispatcher $api, Shield $auth, ResponseBuilder $response)
    {
        parent::__construct($api, $auth, $response);

        $this->_registerModelResolvers();
    }

    protected function _registerModelResolvers()
    {
        // TODO find a better way to resolve the filterer since this seems hacky
        self::setFilterer(app()->router);

        $me = $this;

        $this->beforeFilter(function($route, $request) use ($me)
        {
            $me->_resolveModels($route, $request);
        });
    }

    protected function _resolveModels($route, $request)
    {
        $segments = $request->segments();
        if (count($segments) < 1)
        {
            // todo conditional logic to resolve nested models
        }
        else
        {
            $model = str_singular($segments[0]);
            $this->model = App::make($model);
            $this->parent = false;
        }
    }

    /**
     * Display a listing of specified resources
     *
     * @return Response
     */
    public function index()
    {
        return $this->model->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), $this->model->rules);

        if ($validator->fails())
        {
            // TODO
        }

        $entity = $this->model->create($data);

        return $entity;

        // TODO response messages
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->model->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update($id)
    {
        $entity = $this->model->findOrFail($id);

        $validator = Validator::make($data = Input::all(), $entity::$rules);

        if ($validator->fails())
        {
            // TODO
        }

        $entity->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id)
    {
        // TODO logic for limiting destructive calls
        $result = $this->model->destroy($id);

        // TODO return message (ie. 202)
    }
}