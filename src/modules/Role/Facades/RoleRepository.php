<?php

namespace Modules\Role\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Role\Contracts\RoleRepository as ContractsRoleRepository;

class RoleRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ContractsRoleRepository::class;
    }
}
