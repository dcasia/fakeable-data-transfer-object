<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;

class MultipleFaker extends DataTransferObject
{
    public Enum $enum;

    public File $file;
}
