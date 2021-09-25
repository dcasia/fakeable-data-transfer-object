<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests\Objects;

use DigitalCreative\FakeableDataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Attributes\MapFrom;

class WithMapFrom extends DataTransferObject
{
    #[MapFrom('user.name')]
    public string $name;
}
