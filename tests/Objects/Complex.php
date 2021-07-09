<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;

class Complex extends DataTransferObject
{
    public string $name;

    public Builtin $builtin;
}
