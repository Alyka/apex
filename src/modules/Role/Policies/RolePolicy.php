<?php

namespace Modules\Role\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Facades\RoleService;
use Modules\User\Models\User;
use Modules\Role\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the specified user can view all Role.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(?User $user)
    {
        return false;
    }

    /**
     * The authorization filer.
     *
     * @param User $user
     * @param string $ability
     * @return bool|null
     */
    public function before($user, $ability)
    {
        if (RoleService::is($user, Role::ADMIN))
            return true;

        return null;
    }
}
