<?php

namespace Modules\Role\Services;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;
use Modules\Role\Facades\RoleService;

class RoleUserProvider extends EloquentUserProvider implements UserProvider
{
    /**
     * The provider configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @param  array  $config
     * @return void
     */
    public function __construct(Hasher $hasher, $model, $config)
    {
        parent::__construct($hasher, $model);
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById($identifier)
    {
        return $this->checkRole(
            parent::retrieveById($identifier)
        );
    }

    /**
     * @inheritDoc
     */
    public function retrieveByToken($identifier, $token)
    {
        return $this->checkRole(
            parent::retrieveByToken($identifier, $token)
        );
    }

    /**
     * @inheritDoc
     */
    public function retrieveByCredentials(array $credentials)
    {
        return $this->checkRole(
            parent::retrieveByCredentials($credentials)
        );
    }

    /**
     * @inheritDoc
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return parent::validateCredentials($user, $credentials)
        && ! is_null($this->checkRole($user));
    }

    /**
     * Check if the given user has the set role.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function checkRole($user)
    {
        if ($user && RoleService::is($user, $this->config['role'])) {
            return $user;
        }

        return null;
    }
}
