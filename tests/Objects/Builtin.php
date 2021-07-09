<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;

class Builtin extends DataTransferObject
{
    public string $string;

    public int $int;

    public float $float;

    public bool $bool;

    public array $array;
}
