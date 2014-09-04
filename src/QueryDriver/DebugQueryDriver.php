<?php
namespace Agnostic\QueryDriver;

use Agnostic\QueryDriver\QueryDriverInterface;

class DebugQueryDriver implements QueryDriverInterface
{
    protected $innerQueryDriver;

    public function __construct(QueryDriverInterface $innerQueryDriver)
    {
        $this->innerQueryDriver = $innerQueryDriver;
    }

    /**
     * @return Doctrine\DBAL\Query\QueryBuilder
     */
    public function createNativeQuery($tableName = null)
    {
        return $this->innerQueryDriver->createNativeQuery($tableName);
    }

    public function addWhereIn($query, $field, array $values)
    {
        return $this->innerQueryDriver->addWhereIn($query, $field, $values);
    }

    public function fetchData($query, array $opts = [])
    {
        var_dump((string)$query);
        return $this->innerQueryDriver->fetchData($query, $opts);
    }
}
