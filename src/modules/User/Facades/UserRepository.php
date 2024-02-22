<?php

namespace Modules\User\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\User\Contracts\UserRepository as ContractsUserRepository;

class UserRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ContractsUserRepository::class;
    }
}
