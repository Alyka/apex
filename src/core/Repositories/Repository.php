<?php

namespace Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Core\Contracts\Repository as RepositoryContract;

abstract class Repository implements RepositoryContract
{
    /**
     * The default model for the repository.
     *
     * @var Model
     */
    public Model $model;

    /**
     * Create new instance of the repository.
     *
     * @param Model $model
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Dynamically handle undefined non-static methods.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments): mixed
    {
        return $this->model->{$name}(...$arguments);
    }

    /**
     * Dynamically handle undefined static methods.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments): mixed
    {
        return static::$model::{$name}(...$arguments);
    }
}
