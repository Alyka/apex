<?php

namespace Core\Database\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Core\Database\Eloquent\Concerns\Filterable;
use Core\Database\Eloquent\Concerns\Searchable;
use Core\Database\Query\Builder as QueryBuilder;

trait Modelable
{
    use Searchable, Filterable;

    /**
     * Set the factory class.
     *
     * @param string $factory
     * @return $this
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * Add queries for displaying all records.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAll(Builder $query)
    {
        return $query->select('*');
    }

    /**
     * Add queries for displaying multiple records.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIndex(Builder $query)
    {
        return $query->select('*');
    }

    /**
     * Add queries for displaying details of a single record.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeShow(Builder $query)
    {
        return $query->select('*');
    }

    /**
     * @inheritDoc
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        return new QueryBuilder(
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor()
        );
    }
}
