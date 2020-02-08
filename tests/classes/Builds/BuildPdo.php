<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Builds;

use Ciebit\Legislation\Tests\Settings\Database as DatabaseSettings;
use Exception;
use PDO;

class BuildPdo
{
    private static ?PDO $pdo = null;
    private static ?DatabaseSettings $settings = null;

    private static function getSettings(): DatabaseSettings
    {
        if (self::$settings instanceof DatabaseSettings) {
            return self::$settings;
        }

        return self::$settings = new DatabaseSettings;
    }

    /**
     * @throws Exception
     */
    public static function build(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $data = self::getSettings();

        switch ($data->getDrive()) {
            case 'mysql':
                $dsn = "mysql:dbname={$data->getName()};"
                    . "host={$data->getHost()};"
                    . "port={$data->getPort()};"
                    . "charset={$data->getCharset()}";
                return self::$pdo = new PDO($dsn, $data->getUser(), $data->getPassword());
        }

        throw new Exception('ciebit.legislation.test.unprepared-for-the-database-drive');
    }
}
