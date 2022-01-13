<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use RuntimeException;
use Spatie\DataTransferObject\Attributes\MapFrom;

trait Fakeable
{
    public static function fake(...$args): static
    {
        if (is_array($args[ 0 ] ?? null)) {
            $args = $args[ 0 ];
        }

        $reflection = new ReflectionClass(static::class);
        $properties = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = static::resolvePropertyName($property);
            $type = $property->getType();
            $value = data_get($args, $name, fn() => $property->getDefaultValue());

            if ($type instanceof ReflectionUnionType) {
                throw new RuntimeException('Cannot fake union type');
            } else if ($type instanceof ReflectionNamedType) {
                if (!$type->allowsNull()) {
                    $class = $type->getName();
                    $value = FakerRegistrar::faker($class)(class: $class, value: $value);
                }
            } else {
                $value ??= false;
            }

            $properties[ $name ] = $value;
        }

        return new static($properties);
    }

    private static function resolvePropertyName(ReflectionProperty $property): string
    {
        $attributes = $property->getAttributes(MapFrom::class);

        if (empty($attributes)) {
            return $property->getName();
        }

        return $attributes[ 0 ]->newInstance()->name;
    }
}
