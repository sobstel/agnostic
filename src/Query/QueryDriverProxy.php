<?php
namespace Agnostic\Query;

use Agnostic\Query\QueryDriverInterface;
use Agnostic\Marshaller;
use Agnostic\Entity\Metadata;
use Agnostic\Query\QueryProxy;
use Agnostic\Entity\RepositoryFactory;

class QueryDriverProxy
{
    protected $queryDriver;

    protected $marshaller;

    protected $repositoryFactory;

    public function __construct(QueryDriverInterface $queryDriver, Marshaller $marshaller, RepositoryFactory $repositoryFactory)
    {
        $this->queryDriver = $queryDriver;
        $this->marshaller = $marshaller;
        $this->repositoryFactory =  $repositoryFactory;
    }

    public function createBaseQuery(Metadata $metadata)
    {
        return $this->createProxy($metadata, $this->queryDriver->createBaseQuery($metadata['typeName']));
    }

    public function createFinderQuery(Metadata $metadata, $field, array $values)
    {
        return $this->createProxy($metadata, $this->queryDriver->createFinderQuery($metadata['typeName'], $field, $values));
    }

    public function fetchData(QueryProxy $queryProxy, array $opts = [])
    {
        return $this->queryDriver->fetchData($queryProxy->getQuery(), $opts);
    }

    protected function createProxy(Metadata $metadata, $query)
    {
        return new QueryProxy($query, $metadata, $this, $this->marshaller, $this->repositoryFactory);
    }
}
