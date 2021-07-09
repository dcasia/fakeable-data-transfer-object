<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use RuntimeException;

trait Fakeable
{
    public static function fake(...$args): static
    {
        if (is_array($args[ 0 ] ?? null)) {
            $args = $args[ 0 ];
        }

        /**
         * @var FakerRegistrar $registrar
         */
        $registrar = resolve(FakerRegistrar::class);
        $reflection = new ReflectionClass(static::class);
        $properties = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $property->getName();
            $type = $property->getType();
            $value = data_get($args, $name, fn() => $property->getDefaultValue());

            if ($type instanceof ReflectionUnionType) {
                throw new RuntimeException('Cannot fake union type');
            } else if ($type instanceof ReflectionNamedType) {
                $class = $type->getName();
                $value = $registrar->faker($class)(class: $class, value: $value);
            } else {
                $value ??= false;
            }

            $properties[ $name ] = $value;
        }

        return new static($properties);
    }
}