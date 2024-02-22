<?php

namespace Core\Helpers;

use Carbon\Carbon;
use ReflectionClass;
use RuntimeException;

class Helper
{
    /**
     * Get the base namespace of the modules.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public static function moduleNamespace()
    {
        return 'Modules\\';
    }

    /**
     * Get the base namespace of the core.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public static function coreNamespace()
    {
        return 'Core\\';
    }

    /**
     * Cast boolean literal to tinyint.
     *
     * @param string $value
     * @return bool
     */
    public static function toBool($value)
    {
        return $value == 'true' ? 1 : 0;
    }

    /**
     * Format the given date.
     *
     * @param Carbon|string|int $time
     * @return string
     */
    public static function timeFormat($time)
    {
        return Carbon::parse($time)->format('Y-d-m H:i');
    }

    /**
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *
     * @return array<int|string, mixed>
     */
    public static function arrayMergeRecursiveDistinct(array &$array1, array &$array2): array
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * Get all classes implementing an interface.
     *
     * @param string $interfaceName
     * @return array
     */
    public static function getSubClasses(string $interfaceName)
    {
        return array_filter(
            get_declared_classes(),
            function( $className ) use ( $interfaceName ) {
                return is_subclass_of($className, $interfaceName)
                            && ! (new ReflectionClass($className))->isAbstract();
            }
        );
    }
}
