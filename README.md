# Fakeable Data Transfer Object

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digital-creative/fakeable-data-transfer-object)](https://packagist.org/packages/digital-creative/fakeable-data-transfer-object)
[![Total Downloads](https://img.shields.io/packagist/dt/digital-creative/fakeable-data-transfer-object)](https://packagist.org/packages/digital-creative/fakeable-data-transfer-object)
[![License](https://img.shields.io/packagist/l/digital-creative/fakeable-data-transfer-object)](https://github.com/dcasia/fakeable-data-transfer-object/blob/master/LICENSE)

# Installation

You can install the package via composer:

```
composer require digital-creative/fakeable-data-transfer-object
```

## Dependencies:

* [Laravel 8.0+](https://github.com/laravel/laravel)
* [Data transfer object 3.0+](https://github.com/spatie/data-transfer-object)

## Usage

```php
use DigitalCreative\FakeableDataTransferObject\DataTransferObject;

class SomeObject extends DataTransferObject
{
    public string $name;
    public int $age;
}
```

then

```php
$dto1 = SomeObject::fake();
$dto2 = SomeObject::fake(age: 18);

echo $dto1->name; // random word
echo $dto1->age; // random int

echo $dto2->name; // random word
echo $dto2->age; // 18
```

## Register Custom Types

You can register any custom type by adding the following on the `boot` method of your `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use BenSampo\Enum\Enum;
use DigitalCreative\FakeableDataTransferObject\FakerRegistrar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /**
         * @var FakerRegistrar $registrar
         */
        $registrar = resolve(FakerRegistrar::class);
      
        $registrar->register(Enum::class, function (string $class, mixed $value = null) {
            return blank($value) ? $class::getRandomInstance() : $class::fromValue($value);
        });
        
        $registrar->register(UploadedFile::class, function (string $class, mixed $value = null) {
            return blank($value) ? UploadedFile::fake()->create('file.png') : $value;
        });

    }
}
```

and use like:

```php
use DigitalCreative\FakeableDataTransferObject\DataTransferObject;

class SomeObject extends DataTransferObject
{
    public GenderEnum $gender;
    public UploadedFile $attachment;
}
```

## License

The MIT License (MIT). Please see [License File](https://raw.githubusercontent.com/dcasia/fakeable-data-transfer-object/master/LICENSE) for more information.
