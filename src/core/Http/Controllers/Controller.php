<?php

namespace Core\Http\Controllers;

use BadMethodCallException;
use Core\Contracts\Service;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Service instance.
     *
     * @var Service
     */
    protected $service;

    /**
     * Create new instance of the controller.
     *
     * @param Service $service
     * @return void
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Get the class namespace.
     *
     * @return void
     */
    protected function namespace()
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }

    /**
     * Get the name of the module's base directory.
     *
     * @return string
     */
    protected function moduleBaseName()
    {
        $fileName = (new ReflectionClass($this))->getFileName();
        $fileName = dirname($fileName, 3);

        return Str::afterLast($fileName, DIRECTORY_SEPARATOR);
    }

    /**
     * Dynamically handle undefined method calls.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $requestClass = $this->requestClass($method);

        $request = [];

        if (class_exists($requestClass))
            $request = app($requestClass)->validated();

        $response = $this->service->{$method}(($request), ...$parameters);

        return $this->handleResponse($response, $method);
    }

    /**
     * Get the form request name for the given method.
     *
     * @param string $method
     * @return string
     */
    protected function requestClass($method)
    {
        return Str::beforeLast($this->namespace(), 'Http')
                    . 'Http\\Requests\\'
                    . Str::studly($method)
                    . 'Request';
    }

    /**
     * Handle the api response
     */
    protected function handleResponse($response, $method): mixed
    {
        $resourceClass = $this->resourceClass($method);

        if (class_exists($resourceClass)) {

            if ($method === 'index')
                /** @var ResourceCollection */
                $resource = $resourceClass::collection($response);

            else
                /** @var JsonResource */
                $resource = new $resourceClass($response);

            $statusCode = $resource->response()->getStatusCode();

            return $resource->additional([
                'code' => $statusCode,
                'message' => trans('response.success'),
            ]);
        }

        return $response;
    }

    /**
     * Get the api resource class.
     *
     * @return string
     */
    protected function resourceClass($method = null)
    {
        $baseName = $method ?? $this->moduleBaseName();
        $baseName = Str::studly($baseName);

        return Str::beforeLast($this->namespace(), 'Http')
                    . 'Http\\Resources\\'
                    . $baseName
                    . 'Resource';
    }
}
