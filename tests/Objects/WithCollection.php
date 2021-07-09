<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Collection\ItemCollection;
use Spatie\DataTransferObject\Attributes\CastWith;

class WithCollection extends DataTransferObject
{
    #[CastWith(ItemCollection::class)]
    public ItemCollection $items;
}
