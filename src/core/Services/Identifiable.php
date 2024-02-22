<?php

namespace Core\Services;

use Illuminate\Support\Str;

trait Identifiable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Get the name of the service.
     *
     * @return string
     */
    public static function name()
    {
        return self::$name ?? Str::camel(
            Str::beforeLast(
                Str::afterLast(static::class, '\\'),
                'Service'
            )
        );
    }
}
