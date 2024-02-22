<?php

namespace Modules\Role\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Core\Services\Service;
use Modules\Role\Contracts\RoleRepository;
use Modules\Role\Contracts\RoleService as RoleServiceContract;
use Modules\User\Facades\UserRepository;
use Modules\User\Models\User;

class RoleService extends Service implements RoleServiceContract
{
    /**
     * Create new instance of RoleService
     *
     * @param RoleRepository $repository
     * @return void
     */
    public function __construct(RoleRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Add the specified role to given user.
     *
     * @param array $request
     * @return void
     */
    public function assign($request): void
    {
        $role = $this->repository->where('code', $request['role'])->first();
        $user = UserRepository::findOrFail($request['user_id']);

        if ($this->is($user, $request['role'])) {
            throw ValidationException::withMessages([
                'role' => ['This user already has the specified role.'],
            ]);
        }

        $user->roles()->attach($role);
    }

    /**
     * Remove the specified role from given user.
     *
     * @param array $request
     * @return void
     */
    public function remove($request): void
    {
        $role = $this->repository->where('code', $request['role'])->first();
        $user = UserRepository::findOrFail($request['user_id']);

        if (! $this->is($role->name, $user)) {
            throw ValidationException::withMessages([
                'role' => ['This user doesn\'t have the specified role.'],
            ]);
        }

        $user->roles()->detach($role);
    }

    /**
     * Get all roles assigned to the given entity
     *
     * @param Model $rolable
     * @return array
     */
    public function getRoles(Model $rolable): array
    {
        return $rolable->roles()->pluck('code')->toArray();
    }

    /**
     * Determine if the given user is assigned the specified role.
     *
     * @param User $user
     * @param string $roleCode
     * @return boolean
     */
    public function is(User $user, string $roleCode): bool
    {
        return $user->roles()->where('code', $roleCode)->exists();
    }
}
