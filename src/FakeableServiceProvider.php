<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject;

use Carbon\CarbonInterface;
use Faker\Generator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use ReflectionMethod;
use ReflectionUnionType;

class FakeableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerBuiltinFakers();
        $this->registerCarbonFaker();
        $this->registerDataTransferObjectFaker();
        $this->registerCollectionFaker();
    }

    protected function registerBuiltinFakers(): void
    {
        $faker = $this->app->make(Generator::class);

        $callbacks = [
            'string' => fn(...$args) => $args[ 'value' ] ?? $faker->word,
            'int' => fn(...$args) => $args[ 'value' ] ?? $faker->randomNumber(),
            'float' => fn(...$args) => $args[ 'value' ] ?? $faker->randomFloat(),
            'bool' => fn(...$args) => $args[ 'value' ] ?? $faker->boolean,
            'array' => fn(...$args) => $args[ 'value' ] ?? [],
        ];

        foreach ($callbacks as $type => $callback) {
            FakerRegistrar::register($type, $callback);
        }
    }

    protected function registerCarbonFaker(): void
    {
        FakerRegistrar::register(CarbonInterface::class, function (string $class, $value = null) {
            /**
             * @var CarbonInterface $class
             */
            return blank($value) ? $class::now() : $class::parse($value);
        });
    }

    protected function registerDataTransferObjectFaker(): void
    {
        FakerRegistrar::register(DataTransferObject::class, function (string $class, $value = null) {
            if ($value instanceof DataTransferObject) {
                $value = $value->toArray();
            }

            /**
             * @var DataTransferObject $class
             */
            return $class::fake($value ?? [])->toArray();
        });
    }

    protected function registerCollectionFaker(): void
    {
        FakerRegistrar::register(Collection::class, function (string $class, $value = null) {
            /**
             * @var Collection $class
             */
            $reflection = new ReflectionMethod($class, 'offsetGet');
            $returnType = $reflection->getReturnType();

            if (blank($returnType)) {
                return $class::make($value);
            }

            if ($returnType instanceof ReflectionUnionType) {
                $types = $returnType->getTypes();
            } else {
                $types = [ $returnType ];
            }

            return collect($value ?? [ [] ])->map(function ($value) use ($types) {
                $class = Arr::random($types)->getName();
                $value = FakerRegistrar::faker($class)(class: $class, value: $value);

                if ($value instanceof DataTransferObject) {
                    $value = $value->toArray();
                }

                return $value;
            })->toArray();
        });
    }
}
