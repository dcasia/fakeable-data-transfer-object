<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests;

use DigitalCreative\FakeableDataTransferObject\FakerRegistrar;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Enum;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\File;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        FakerRegistrar::register(Enum::class, function ($class, $value = null) {
            return new $class($value);
        });

        FakerRegistrar::register(File::class, function ($class, $value = null) {
            return new $class;
        });
    }
}
