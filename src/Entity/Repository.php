<?php
namespace Agnostic\Entity;

use Agnostic\EntityManager;

class Repository
{
    protected $typeName;

    protected $em;

    public function __construct($typeName, EntityManager $em)
    {
        $this->typeName = $typeName;
        $this->em = $em;
    }

    public function createQuery()
    {
        return $this->em->getQueryDriver()->createQuery($this->typeName);
    }

    public function findBy($field, array $values)
    {
        $queryDriver = $this->em->getQueryDriver();

        $query = $queryDriver->createFinderQuery($this->typeName, $field, $values);
        $data = $queryDriver->fetchData($query);

        return $data;
    }
}
