<?php
namespace Agnostic\Entity;

use Agnostic\EntityManager;
use Agnostic\Entity\Metadata;
use Agnostic\Marshaller;
use Agnostic\Query\QueryDriverInterface;

class Repository
{
    protected $metadata;

    protected $queryDriver;

    public function __construct(Metadata $metadata, QueryDriverInterface $queryDriver)
    {
        $this->metadata = $metadata;
        $this->queryDriver = $queryDriver;
    }

    public function createBaseQuery()
    {
        return $this->queryDriver->createBaseQuery($this->metadata['typeName']);
    }

    public function findBy($field, array $values)
    {
        return $this->queryDriver->createFinderQuery($this->metadata['typeName'], $field, $values);
    }

    public function find(array $values)
    {
        return $this->findBy($this->metadata['id'], $values);
    }
}
