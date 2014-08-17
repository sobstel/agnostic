<?php
namespace Agnostic\Entity;

use Agnostic\EntityManager;

class EntityRepository
{
    protected $name;

    protected $em;

    public function __construct($name, EntityManager $em)
    {
        $this->name = $name;
        $this->em = $em;
    }

    public function baseQuery()
    {
        return $this->em->getQueryDriver()->baseQuery($this->name);
    }

    // finderQuery
}
