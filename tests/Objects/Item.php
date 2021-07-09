<?php

declare(strict_types=1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;

class Item extends DataTransferObject
{
    public Builtin $builtin;
}
