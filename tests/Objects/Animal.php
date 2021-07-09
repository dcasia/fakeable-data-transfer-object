<?php

declare(strict_types=1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;

abstract class Animal extends DataTransferObject
{
    public string $type;
    public string $name;
}
