<?php

namespace Modules\Role\Repositories;

use Core\Repositories\Repository;
use Modules\Role\Contracts\RoleRepository;
use Modules\Role\Models\Role;

class RoleRepositoryEloquent extends Repository implements RoleRepository
{
    /**
     * Create new instance of the repository.
     *
     * @return void
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
