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
    public function register(): void
    {
        $this->app->singleton(FakerRegistrar::class, function () {
            return new FakerRegistrar();
        });
    }

    public function boot(): void
    {
        $this->registerBuiltinFakers();
        $this->registerCarbonFaker();
        $this->registerDataTransferObjectFaker();
        $this->registerCollectionFaker();
    }

    protected function registerBuiltinFakers(): void
    {
        /**
         * @var FakerRegistrar $registrar
         */
        $registrar = resolve(FakerRegistrar::class);
        $faker = $this->app->make(Generator::class);

        $callbacks = [
            'string' => fn(...$args) => $args[ 'value' ] ?? $faker->word,
            'int' => fn(...$args) => $args[ 'value' ] ?? $faker->randomNumber(),
            'float' => fn(...$args) => $args[ 'value' ] ?? $faker->randomFloat(),
            'bool' => fn(...$args) => $args[ 'value' ] ?? $faker->boolean,
            'array' => fn(...$args) => $args[ 'value' ] ?? [],
        ];

        foreach ($callbacks as $type => $callback) {
            $registrar->register($type, $callback);
        }
    }

    protected function registerCarbonFaker(): void
    {
        /**
         * @var FakerRegistrar $registrar
         */
        $registrar = resolve(FakerRegistrar::class);
        $registrar->register(CarbonInterface::class, function (string $class, $value = null) {
            if (blank($value)) {
                return now();
            }

            /**
             * @var CarbonInterface $class
             */
            return $class::parse($value);
        });
    }

    protected function registerDataTransferObjectFaker(): void
    {
        /**
         * @var FakerRegistrar $registrar
         */
        $registrar = resolve(FakerRegistrar::class);
        $registrar->register(DataTransferObject::class, function (string $class, $value = null) {
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
        /**
         * @var FakerRegistrar $registrar
         */
        $registrar = resolve(FakerRegistrar::class);
        $registrar->register(Collection::class, function (string $class, $value = null) use ($registrar) {
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

            return collect($value ?? [ [] ])->map(function ($value) use ($types, $registrar) {
                $class = Arr::random($types)->getName();
                $value = $registrar->faker($class)(class: $class, value: $value);

                if ($value instanceof DataTransferObject) {
                    $value = $value->toArray();
                }

                return $value;
            })->toArray();
        });
    }
}