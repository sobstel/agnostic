<?php
namespace Agnostic\QueryDriver;

use Agnostic\QueryDriver\QueryDriverInterface;

class DebugQueryDriver implements QueryDriverInterface
{
    protected $inner_query_driver;

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
        var_dump((string)$query);
        return $this->inner_query_driver->fetchData($query, $opts);
    }
}
