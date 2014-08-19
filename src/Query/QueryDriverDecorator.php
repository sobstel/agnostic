<?php
namespace Agnostic\Query;

use Agnostic\Query\QueryDriverInterface;
use Agnostic\Query\QueryDecorator;

class QueryDriverDecorator implements QueryDriverInterface
{
    protected $innerQueryDriver;

    public function __construct(QueryDriverInterface $innerQueryDriver)
    {
        $this->innerQueryDriver = $innerQueryDriver;
    }

    public function createQuery($typeName)
    {
        return $this->decorateQuery($this->innerQueryDriver->createQuery($typeName));
    }

    public function createFinderQuery($typeName, $field, array $values)
    {
        return $this->decorateQuery($this->innerQueryDriver->createFinderQuery($typeName, $field, $values));
    }

    public function fetchData($query, array $opts = [])
    {
        return $this->innerQueryDriver->fetchData($query, $opts);
    }

    protected function decorateQuery($query)
    {
        return new QueryDecorator($query, $this->innerQueryDriver);
    }
}
