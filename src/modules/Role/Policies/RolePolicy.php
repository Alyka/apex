<?php

namespace Modules\Role\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\Contracts\UserRepository;
use Modules\User\Models\User;
use Modules\Role\Models\Role;
use Modules\User\Facades\UserService;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Determine if the specified user can create role.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(?User $user)
    {
        return false;
    }

    /**
     * Determine if the specified user can update the specified role.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function update(?User $user, Role $role)
    {
        return $this->userRepository->owns($user, $role);
    }

    /**
     * Determine if the specified user can view all Role.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine if the specified user can view any role.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function view(?User $user, Role $role)
    {
        return true;
    }

    /**
     * Determine if the specified user can delete the specified role.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    public function delete(?User $user, Role $role)
    {
        return $this->userRepository->owns($user, $role);
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
        return UserService::is('admin', $user);
    }
}
