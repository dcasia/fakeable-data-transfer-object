<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Collection\MultiReturnTypeCollection;
use Spatie\DataTransferObject\Attributes\CastWith;

class Zoo extends DataTransferObject
{
    #[CastWith(MultiReturnTypeCollection::class)]
    public MultiReturnTypeCollection $animals;
}
