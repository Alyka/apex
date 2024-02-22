<?php

namespace Core\Contracts;

use Illuminate\Support\Collection;

interface Repository
{
    /**
     * Dynamically handle undefined non-static methods.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments): mixed;

    /**
     * Dynamically handle undefined static methods.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments): mixed;
}
