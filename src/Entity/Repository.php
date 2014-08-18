<?php
namespace Agnostic\Entity;

use Agnostic\EntityManager;
use Agnostic\Entity\Metadata;
use Agnostic\QueryDriver\QueryDriverInterface;

class Repository
{
    protected $typeName;

    protected $metadata;

    protected $queryDriver;

    public function __construct(Metadata $metadata, QueryDriverInterface $queryDriver)
    {
        $this->typeName = $metadata['typeName'];
        $this->metadata = $metadata;
        $this->queryDriver = $queryDriver;
    }

    public function createQuery()
    {
        return $this->queryDriver->createQuery($this->typeName);
    }

    public function findBy($field, array $values)
    {
        $query = $this->queryDriver->createFinderQuery($this->typeName, $field, $values);
        $data = $this->queryDriver->fetchData($query);

        return $data;
    }

    public function find(array $values)
    {
        return $this->findBy($this->metadata['id'], $values);
    }
}
