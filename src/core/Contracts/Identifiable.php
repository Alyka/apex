<?php

namespace Core\Contracts;

interface Identifiable
{
    /**
     * Get the name of the service.
     *
     * @return string
     */
    public static function name();
}
