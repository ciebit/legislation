# Legislation

Representation of Laws, Decrees, Ordinances and Constitution.


## Config Tests

1. Copy file `tests/settings.model.php` to `tests/settings.php`;
2. Edit the `tests/settings.php` file by adding the database settings;
3. Run `composer install`;
4. Run `./vendor/bin/phpunit`;


## Example Storage

```php
require __DIR__.'/vendor/autoload.php';

use Ciebit\Legislation\Documents\Factories\Law as LawFactory;
use Ciebit\Legislation\Documents\Storages\Databases\Sql;

$lawFactory = new Factory();
$lawFactory->setTitle('Law 12.345/2020')
    ->setDateTime(new DateTime('2020-02-06'))
    ->setStatus(Status::ACTIVE())
    ->setNumber(12345)
    ->setSlug('law-2020')
    ->setDescription('Defines rules for the construction of public schools.');

$law = $lawFactory->create();

$storage = new Sql(new PDO(/** your settings */));
$id = $storage->store($law);
```


## Example Find

```php
require __DIR__.'/vendor/autoload.php';

use Ciebit\Legislation\Documents\Storages\Databases\Sql;
use Ciebit\Legislation\Documents\Decree;
use Ciebit\Legislation\Documents\Status;

$storage = new Sql(new PDO(/** your settings */));
$documentCollection = $storage
    ->addFilterByType('=', Decree::class)
    ->addFilterByStatus(Status::ACTIVE())
    ->find();

foreach($documentCollection as $decree) {
    echo "{$decree->getTitle()}" . PHP_EOL;
}
```
