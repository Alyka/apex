<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRole
{
    /**
     * Filter the query by role.
     *
     * @param Builder $builder
     * @param string $role
     * @return Builder
     */
    public function filterByRole(Builder $builder, $role)
    {
        return $builder->whereHas('roles', function ($builder) use ($role) {
            $builder->where('name', $role);
        });
    }

    /**
     * Get all related roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
