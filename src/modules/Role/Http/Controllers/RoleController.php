<?php

namespace Modules\Role\Http\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Resources\SuccessResource;
use Modules\Role\Contracts\RoleService;
use Modules\Role\Http\Requests\AssignRequest;

class RoleController extends Controller
{
    /**
     * @var RoleService
     */
    protected $service;

    /**
     * Create new instance of the Role controller.
     *
     * @param RoleService $service
     * @return void
     */
    public function __construct(RoleService $service)
    {
        parent::__construct($service);
    }

    /**
     * Assign role to user.
     *
     * @param AssignRequest $request
     * @return SuccessResource
     */
    public function assign(AssignRequest $request)
    {
        return $this->service->assign($request->validated());
    }

    /**
     * Remove role from user.
     *
     * @param AssignRequest $request
     * @return SuccessResource
     */
    public function remove(AssignRequest $request)
    {
        return $this->service->remove($request->validated());
    }
}
