<?php

use Modules\Role\Models\Role;
use Modules\User\Models\User;

return [
    // Token expiry time in seconds
    'token_expiry_time' => 3600,

    'guards' => [
        'user' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'passport',
            'provider' => 'admins',
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'role_user',
            'model' => User::class,
            'role' => Role::USER,
        ],
        'admins' => [
            'driver' => 'role_user',
            'model' => User::class,
            'role' => Role::ADMIN,
        ],
    ],
];
