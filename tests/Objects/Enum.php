<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

class Enum
{
    public function __construct(
        public ?string $name = null
    )
    {
    }
}
