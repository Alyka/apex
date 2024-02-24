<?php

namespace Modules\User\Services;

use Core\Services\Service;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Contracts\UserRepository;
use Modules\User\Contracts\UserService as UserServiceContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Role\Facades\RoleService;
use Modules\Role\Models\Role;

class UserService extends Service implements UserServiceContract
{
    /**
     * Create new instance of UserService.
     *
     * @return void
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @inheritDoc
     */
    public function store($request): Model
    {
        DB::beginTransaction();

        $request['password'] = Hash::make($request['password']);

        $user = parent::store($request);

        if (! empty(@$request['roles'])) {
            foreach ($request['roles'] as $role) {
                RoleService::assign([
                    'user_id' => $user->id,
                    'role' => $role,
                ]);
            }
        }

        DB::commit();

        return $user;
    }

    /**
     * Register a new user.
     *
     * @param array $request
     * @return Model
     */
    public function register(array $request): Model
    {
        DB::beginTransaction();

        $request['password'] = Hash::make($request['password']);

        $user = parent::store($request);

        RoleService::assign([
            'user_id' => $user->id,
            'role' => Role::USER,
        ]);

        DB::commit();

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function update(array $request, int $id): Model
    {
        DB::beginTransaction();

        if (isset($request['password']))
            $request['password'] = Hash::make($request['password']);

        $user = parent::update($request, $id);

        if (! empty(@$request['roles'])) {
            RoleService::sync($request['roles'], $user);
        }

        DB::commit();

        return $user;
    }
}
