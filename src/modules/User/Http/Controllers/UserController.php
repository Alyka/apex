<?php

namespace Modules\User\Http\Controllers;

use Modules\User\Contracts\UserService;
use Core\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * UserService instance.
     *
     * @var UserService
     */
    protected $service;

    /**
     * Create new controller instance.
     *
     * @param UserService $userService
     * @return void
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }
}
