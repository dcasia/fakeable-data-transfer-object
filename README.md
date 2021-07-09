# Fakeable data transfer object

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