<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject;

use Spatie\DataTransferObject\DataTransferObject as BaseDataTransferObject;

abstract class DataTransferObject extends BaseDataTransferObject
{
    use Fakeable;
}
