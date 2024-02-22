<?php

namespace Core\Database\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Filterable
{
    /**
     * filter the query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|string $key
     * @param string|null $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, $key, $value = null)
    {
        if (is_array($key) && ! empty($key)) {
            foreach ($key as $param => $value) {
                if (is_scalar($value) && (! $value || ! strlen($value))) continue;

                $this->applyFilter($query, $param, $value);
            }
        } elseif ($value) {
            $this->applyFilter($query, $key, $value);
        }

        return $query;
    }

    /**
     * Apply the filter.
     *
     * @param Builder $query
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function applyFilter(Builder $query, string $key, $value)
    {
        $paramKeyStudly = Str::studly($key);
        $method = "filterBy{$paramKeyStudly}";
        $scope = "scopeFilterBy{$paramKeyStudly}";

        // Ensure that only fields found in the model's
        // 'filterable' property, or whose filterBy or scopeFilterBy method
        // is defined, are effective in the filtering operation.
        if (method_exists($this, $method)) {
            $this->$method($query, $value);
        } elseif (method_exists($this, $scope)) {
            $query->$method($value);
        } elseif (in_array($key, ($this->filterable ?? []))) {
            $query->where($key, $value);
        }
    }

    /**
     * Query for records belonging to the given model.
     *
     * @param Builder $query
     * @param Model $model
     * @param string $relation
     * @return void
     */
    public function scopeFor(Builder $query, Model $model, string $relation)
    {
        return $query->whereHas($relation, function (Builder $query) use ($model) {
            $query->whereKey($model->id);
        });
    }
}
