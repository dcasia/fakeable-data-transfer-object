<?php

namespace DigitalCreative\FakeableDataTransferObject;

use RuntimeException;

class FakerRegistrar
{
    protected static array $fakers = [];

    public static function register(string $abstract, callable $faker): void
    {
        static::$fakers[$abstract] = $faker;
    }

    public static function faker(string $abstract): callable
    {
        $original = $abstract;
        $faker = static::$fakers[$abstract] ?? null;

        if (blank($faker)) {
            foreach (class_implements($abstract) as $interface) {
                if (($faker = static::$fakers[$interface] ?? null)) {
                    break;
                }
            }
        }

        if (blank($faker)) {
            while ($abstract = get_parent_class($abstract)) {
                if (($faker = static::$fakers[$abstract] ?? null)) {
                    break;
                }
            }
        }

        return $faker ?? fn(...$args) => throw new RuntimeException("Cannot fake '$original'");
    }

    public static function flush(): void
    {
        static::$fakers = [];
    }
}
