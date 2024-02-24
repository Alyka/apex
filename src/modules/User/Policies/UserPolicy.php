<?php

namespace Modules\User\Policies;

use Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Facades\RoleService;
use Modules\Role\Models\Role;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the specified user can create a user.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(?User $user)
    {
        return false;
    }

    /**
     * Determine if the current user can view a given user.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(?User $user)
    {
        return false;
    }

    /**
     * Determine if the current user can view another user.
     *
     * @param  User  $user
     * @return bool
     */
    public function view(?User $currentUser, User $user)
    {
        return $currentUser->is($user);
    }

    /**
     * Determine if the current user can update the given user.
     *
     * @param  User  $currentUser
     * @param  User  $user
     * @return bool
     */
    public function update(?User $currentUser, User $user)
    {
        return false;
    }

    /**
     * Determine if the specified user can delete the specified user.
     *
     * @param  User  $currentUser
     * @param  User  $user
     * @return bool
     */
    public function delete(?User $currentUser, User $user)
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
        // If the user is admin, we will return true, else we will return null
        // so that other policies can apply accordingly.
        if (RoleService::is($user, Role::ADMIN))
            return true;

        return null;
    }
}
