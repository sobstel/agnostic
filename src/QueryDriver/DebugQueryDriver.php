<?php
namespace Agnostic\QueryDriver;

use Agnostic\QueryDriver\QueryDriverInterface;

class DebugQueryDriver implements QueryDriverInterface
{
    protected $inner_query_driver;

    protected static $queries;

    public function __construct(QueryDriverInterface $inner_query_driver)
    {
        $this->inner_query_driver = $inner_query_driver;
    }

    /**
     * @return Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQuery($tableName = null)
    {
        return $this->inner_query_driver->createQuery($tableName);
    }

    public function addWhereIn($query, $field, array $values)
    {
        return $this->inner_query_driver->addWhereIn($query, $field, $values);
    }

    public function fetchData($query, array $opts = [])
    {
        $start = microtime(true);

        $result = $this->inner_query_driver->fetchData($query, $opts);

        $time = microtime(true) - $start;

        self::$queries[] = [
            'driver' => str_replace(['Agnostic\QueryDriver\\', 'QueryDriver'], '', get_class($this->inner_query_driver)),
            'query' => $this->toSql($query),
            'time' => $time
        ];

        return $result;
    }

    public function toSql($query)
    {
        return $this->inner_query_driver->toSql($query);
    }

    public static function getQueries()
    {
        return self::$queries;
    }
}
