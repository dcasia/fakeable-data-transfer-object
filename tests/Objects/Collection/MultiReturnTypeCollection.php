<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects\Collection;

use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Cat;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Dog;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Caster;

class MultiReturnTypeCollection extends Collection implements Caster
{
    public function offsetGet($key): Cat|Dog
    {
        return parent::offsetGet($key);
    }

    public function cast(mixed $value): static
    {
        return static::make($value)->map(function (array $value) {
            return match ($value[ 'type' ]) {
                Cat::class => new Cat($value),
                Dog::class => new Dog($value),
            };
        });
    }
}
