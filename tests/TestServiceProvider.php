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
        resolve(FakerRegistrar::class)->register(Enum::class, function($class, $value = null) {
            return new $class($value);
        });

        resolve(FakerRegistrar::class)->register(File::class, function($class, $value = null) {
            return new $class;
        });
    }
}
