<?php
namespace Agnostic\Query;

use Agnostic\Query\QueryDriverInterface;
use Agnostic\Marshaller;
use Agnostic\Query\QueryDecorator;

class QueryDriverDecorator implements QueryDriverInterface
{
    protected $innerQueryDriver;

    protected $marshaller;

    public function __construct(QueryDriverInterface $innerQueryDriver, Marshaller $marshaller)
    {
        $this->innerQueryDriver = $innerQueryDriver;
        $this->marshaller = $marshaller;
    }

    public function createBaseQuery($typeName)
    {
        return $this->decorateQuery($typeName, $this->innerQueryDriver->createBaseQuery($typeName));
    }

    public function createFinderQuery($typeName, $field, array $values)
    {
        return $this->decorateQuery($typeName, $this->innerQueryDriver->createFinderQuery($typeName, $field, $values));
    }

    public function fetchData($query, array $opts = [])
    {
        return $this->innerQueryDriver->fetchData($query, $opts);
    }

    protected function decorateQuery($typeName, $query)
    {
        return new QueryDecorator($query, $typeName, $this, $this->marshaller);
    }
}
