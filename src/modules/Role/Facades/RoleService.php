<?php

namespace Modules\Role\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Role\Contracts\RoleService as RoleServiceContract;

class RoleService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return RoleServiceContract::class;
    }
}
