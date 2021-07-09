<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects\Collection;

use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Item;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Caster;

class ItemCollection extends Collection implements Caster
{
    public function offsetGet($key): Item
    {
        return parent::offsetGet($key);
    }

    public function cast(mixed $value): static
    {
        return static::make($value)->mapInto(Item::class);
    }
}
