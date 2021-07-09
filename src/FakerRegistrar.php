<?php

namespace DigitalCreative\FakeableDataTransferObject;

use RuntimeException;

class FakerRegistrar
{
    protected array $fakers = [];

    public function register(string $abstract, callable $faker): self
    {
        $this->fakers[ $abstract ] = $faker;

        return $this;
    }

    public function faker(string $abstract): callable
    {
        $original = $abstract;
        $faker = $this->fakers[ $abstract ] ?? null;

        if (blank($faker)) {
            foreach (class_implements($abstract) as $interface) {
                if (($faker = $this->fakers[ $interface ] ?? null)) {
                    break;
                }
            }
        }

        if (blank($faker)) {
            while ($abstract = get_parent_class($abstract)) {
                if (($faker = $this->fakers[ $abstract ] ?? null)) {
                    break;
                }
            }
        }

        return $faker ?? fn(...$args) => throw new RuntimeException("Cannot fake '$original'");
    }

    public function flush(): self
    {
        $this->fakers = [];

        return $this;
    }
}
