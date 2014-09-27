<?php
namespace Agnostic\Tests;

use PDO;
use Illuminate\Database\Capsule\Manager as Capsule;

class Registry
{
    protected static $manager;

    protected static $conn;

    public static function manager()
    {
        if (!self::$manager) {
            self::$manager = new \Agnostic\Manager(self::importTypes());
            self::$manager->getQueryDriverManager()->debug();

            // doctrine
            $conn = \Doctrine\DBAL\DriverManager::getConnection(['pdo' => self::conn()]);
            $query_driver = new \Agnostic\QueryDriver\DoctrineQueryDriver($conn);
            self::$manager->getQueryDriverManager()->set($query_driver, 'doctrine');

            // illuminate
            self::setUpIlluminateConn();
            $query_driver = new \Agnostic\QueryDriver\IlluminateQueryDriver();
            self::$manager->getQueryDriverManager()->set($query_driver, 'illuminate');

            // raw
            $query_driver = new \Agnostic\QueryDriver\RawQueryDriver(self::conn());
            self::$manager->getQueryDriverManager()->set($query_driver, 'raw');

            // handler
            $query_driver = new \Agnostic\QueryDriver\HandlerQueryDriver(self::conn());
            self::$manager->getQueryDriverManager()->set($query_driver, 'handler');

            // default
            self::$manager->getQueryDriverManager()->alias('default', 'doctrine');
        }

        return self::$manager;
    }

    protected static function importTypes()
    {
        return include(__DIR__.'/Model/config.php');
    }

    public static function conn()
    {
        if (!self::$conn) {
            self::$conn = new PDO('sqlite::memory:');
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::importFixtures(self::$conn);
        }

        return self::$conn;
    }

    /**
     * Set up illuminate connection separately as it's impossible to pass existing
     * PDO instance to it.
     */
    protected static function setUpIlluminateConn()
    {
        $capsule = new Capsule;
        $capsule->addConnection(['driver' => 'sqlite', 'database'  => ':memory:']);
        $capsule->setAsGlobal();
        self::importFixtures($capsule->getConnection()->getPdo());
    }

    protected static function importFixtures($conn)
    {
        $fixtures = [
            __DIR__.'/../fixtures/sqlite-sakila-schema.sql',
            __DIR__.'/../fixtures/sqlite-sakila-insert-data.sql',
        ];

        foreach ($fixtures as $sql) {
            $conn->exec(file_get_contents($sql));
        }
    }
}
