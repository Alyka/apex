<?php

namespace Modules\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Auth\Contracts\AuthService as AuthServiceContract;

class AuthService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AuthServiceContract::class;
    }
}
