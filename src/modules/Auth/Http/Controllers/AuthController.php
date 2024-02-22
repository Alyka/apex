<?php

namespace Modules\Auth\Http\Controllers;

use Core\Http\Controllers\Controller;
use Modules\Auth\Contracts\AuthService;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $service;

    /**
     * Create new instance of the Auth controller.
     *
     * @param AuthService $service
     * @return void
     */
    public function __construct(AuthService $service)
    {
        parent::__construct($service);
    }
}
