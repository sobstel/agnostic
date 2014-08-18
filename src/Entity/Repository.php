<?php
namespace Agnostic\Entity;

use Agnostic\EntityManager;
use Agnostic\QueryDriver\QueryDriverInterface;

class Repository
{
    protected $typeName;

    protected $queryDriver;

    public function __construct($typeName, QueryDriverInterface $queryDriver)
    {
        $this->typeName = $typeName;
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
}
