<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Core\Models\Model;
use Modules\Role\Database\Factories\RoleFactory;

class Role extends Model
{
    use HasFactory;

    const ADMIN = 'admin';
    const USER = 'user';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new RoleFactory;
    }
}
