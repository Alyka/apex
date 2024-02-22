<?php

namespace Modules\Role\Contracts;

use Illuminate\Database\Eloquent\Model;
use Core\Contracts\Service;
use Modules\User\Models\User;

interface RoleService extends Service
{
    /**
     * Add the specified role to given user.
     *
     * @param array $request
     * @return void
     */
    public function assign($request): void;

    /**
     * Remove the specified role from given user.
     *
     * @param array $request
     * @return void
     */
    public function remove($request): void;

    /**
     * Get all roles assigned to the given entity
     *
     * @param Model $rolable
     * @return array
     */
    public function getRoles(Model $rolable): array;

    /**
     * Determine if the given user is assigned the specified role.
     *
     * @param User $user
     * @param string $roleCode
     * @return boolean
     */
    public function is(User $user, string $roleCode): bool;
}
