<?php
namespace Agnostic\Tests;

use PDO;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected static $dbh;

    public static function setUpBeforeClass()
    {
        $dbh = new PDO('sqlite::memory:');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach (glob(__DIR__.'/../fixtures/*.sql') as $sql) {
            $dbh->exec(file_get_contents($sql));
        }

        self::$dbh = $dbh;
    }

    public static function tearDownAfterClass()
    {
        self::$dbh = NULL;
    }
}
