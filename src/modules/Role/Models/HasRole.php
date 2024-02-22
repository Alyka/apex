<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRole
{
    /**
     * Select all resources who have a role of admin
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAdmin(Builder $query)
    {
        return $query->whereHas('roles', function ($query) {
                $query->whereCode(Role::ADMIN);
            });
    }

    /**
     * Select all resources who have a role of user
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeUserOnly(Builder $query)
    {
        return $query->whereHas('roles', function ($query) {
                $query->whereCode(Role::USER);
            })
            ->whereDoesntHave('roles', function ($query) {
                $query->whereCode(Role::ADMIN);
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
