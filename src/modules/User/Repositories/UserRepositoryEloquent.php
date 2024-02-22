<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;
use Core\Repositories\Repository;
use Modules\User\Contracts\UserRepository;

class UserRepositoryEloquent extends Repository implements UserRepository
{
    /**
     * Create new instance of the repository.
     *
     * @return void
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
