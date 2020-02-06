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

use Ciebit\Legislation\Law;
use Ciebit\Legislation\Storages\Databases\Sql;

$law = new Law(
    'Law 12.345/2020',
    new DateTime('2020-02-06'),
    Status::ACTIVE(),
    12345,
    'law-2020',
    'Defines rules for the construction of public schools.',
);

$storage = new Sql(new PDO(/** your settings */));
$id = $storage->store($law);
```
