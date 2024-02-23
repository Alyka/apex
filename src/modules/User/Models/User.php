<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Passport\HasApiTokens;
use Modules\Role\Models\HasRole;
use Modules\User\Database\Factories\UserFactory;

class User extends Authenticatable implements AuthenticatableContract
{
    use HasFactory,
        HasApiTokens,
        HasRole;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Attributes the model can be filtered by.
     *
     * @var array[]
     */
    protected $filterable = [];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new UserFactory;
    }
}
