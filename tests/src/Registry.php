<?php
namespace Agnostic\Tests;

use PDO;

class Registry
{
    protected static $manager;

    protected static $conn;

    public static function manager()
    {
        if (!self::$manager) {
            self::$manager = new \Agnostic\Manager(self::importTypes());

            // doctrine
            $conn = \Doctrine\DBAL\DriverManager::getConnection(['pdo' => self::conn()]);
            $query_driver = new \Agnostic\QueryDriver\DoctrineQueryDriver($conn);
            self::$manager->getQueryDriverManager()->set($query_driver, 'doctrine', true);

            self::$manager->getQueryDriverManager()->alias('default', 'doctrine');
        }

        return self::$manager;
    }

    protected static function importTypes()
    {
        return include(__DIR__.'/../fixtures/types.php');
    }

    public static function conn()
    {
        if (!self::$conn) {
            self::$conn = new PDO('sqlite::memory:');
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::importFixtures();
        }

        return self::$conn;
    }

    protected static function importFixtures()
    {
        $fixtures = [
            __DIR__.'/../fixtures/sqlite-sakila-schema.sql',
            __DIR__.'/../fixtures/sqlite-sakila-insert-data.sql',
        ];

        foreach ($fixtures as $sql) {
            self::$conn->exec(file_get_contents($sql));
        }
    }
}
