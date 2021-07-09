<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;

class SameFaker extends DataTransferObject
{
    public Enum $enum1;

    public Enum $enum2;
}
