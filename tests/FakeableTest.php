<?php

declare(strict_types = 1);

namespace DigitalCreative\FakeableDataTransferObject\Tests;

use DigitalCreative\FakeableDataTransferObject\FakeableServiceProvider;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Builtin;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Cat;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Collection\ItemCollection;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Complex;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\DefaultValue;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Dog;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Enum;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Faker;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\File;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Item;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\MultipleFaker;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\NoFakerProvided;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\NoType;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Nullable;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\SameFaker;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\WithCollection;
use DigitalCreative\FakeableDataTransferObject\Tests\Objects\Zoo;
use Orchestra\Testbench\TestCase;
use RuntimeException;

class FakeableTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FakeableServiceProvider::class,
            TestServiceProvider::class,
        ];
    }

    public function test_default_value_works(): void
    {
        $dto = DefaultValue::fake();

        $this->assertEquals('name', $dto->name);
    }

    public function test_no_type_hint_always_has_false_value(): void
    {
        $dto = NoType::fake();

        $this->assertEquals(false, $dto->name);
    }

    public function test_builtin_works(): void
    {
        $dto = Builtin::fake();

        $this->assertTrue(is_string($dto->string));
        $this->assertTrue(is_int($dto->int));
        $this->assertTrue(is_float($dto->float));
        $this->assertTrue(is_bool($dto->bool));
        $this->assertTrue(is_array($dto->array));
    }

    public function test_nullable_works(): void
    {
        $dto = Nullable::fake();

        $this->assertTrue(is_string($dto->name));
    }

    public function test_nested_dto_works(): void
    {
        $dto1 = Complex::fake(
            builtin: [ 'int' => 5 ]
        );

        $dto2 = Complex::fake(
            builtin: Builtin::fake(int: 8)
        );

        $this->assertTrue(is_string($dto1->name));
        $this->assertTrue(is_string($dto2->name));

        $this->assertTrue(is_string($dto1->builtin->string));
        $this->assertTrue(is_string($dto2->builtin->string));

        $this->assertTrue(is_float($dto1->builtin->float));
        $this->assertTrue(is_float($dto2->builtin->float));

        $this->assertEquals(5, $dto1->builtin->int);
        $this->assertEquals(8, $dto2->builtin->int);
    }

    public function test_faker_works(): void
    {
        $name = 'hello';
        $dto1 = Faker::fake();
        $dto2 = Faker::fake(
            enum: $name
        );

        $this->assertInstanceOf(Enum::class, $dto1->enum);
        $this->assertInstanceOf(Enum::class, $dto2->enum);
        $this->assertEquals($name, $dto2->enum->name);
    }

    public function test_multiple_class_faker_works(): void
    {
        $dto = MultipleFaker::fake();

        $this->assertInstanceOf(Enum::class, $dto->enum);
        $this->assertInstanceOf(File::class, $dto->file);
    }

    public function test_properties_with_same_faker_works(): void
    {
        $dto = SameFaker::fake();

        $this->assertInstanceOf(Enum::class, $dto->enum1);
        $this->assertInstanceOf(Enum::class, $dto->enum2);

        $this->assertNotSame($dto->enum1, $dto->enum2);
    }

    public function test_collection_faker_works(): void
    {
        $dto1 = WithCollection::fake();
        $dto2 = WithCollection::fake(
            items: [
                [
                    'builtin' => [
                        'int' => 5,
                    ],
                ],
                [
                    'builtin' => [
                        'int' => 6,
                    ],
                ],
            ]
        );

        $this->assertInstanceOf(ItemCollection::class, $dto1->items);
        $this->assertCount(1, $dto1->items);
        $this->assertInstanceOf(Item::class, $dto1->items->first());

        $this->assertCount(2, $dto2->items);
        $this->assertEquals(5, $dto2->items->shift()->builtin->int);
        $this->assertEquals(6, $dto2->items->shift()->builtin->int);
    }

    public function test_multiple_return_type_works(): void
    {
        $dto = Zoo::fake(
            animals: [
                [ 'type' => Cat::class ],
                [ 'type' => Dog::class ],
            ]
        );

        $this->assertCount(2, $dto->animals);

        $cat = $dto->animals->shift();
        $this->assertInstanceOf(Cat::class, $cat);
        $this->assertTrue(is_string($cat->name));

        $dog = $dto->animals->shift();
        $this->assertInstanceOf(Dog::class, $dog);
        $this->assertTrue(is_string($dog->name));
    }

    public function test_exception_will_be_thrown_if_no_faker_was_provided(): void
    {
        $this->expectException(RuntimeException::class);

        NoFakerProvided::fake();
    }
}
