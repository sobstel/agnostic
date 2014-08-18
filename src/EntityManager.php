<?php
namespace Agnostic;

use Agnostic\Marshaller;
use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\Entity\NameResolver;
use Agnostic\Entity\Metadata;

// GOD class
class EntityManager
{
    protected $marshaller;

    protected $queryDriver;

    protected $nameResolver;

    protected $repositories;

    protected $metadatas;

    public function __construct(QueryDriverInterface $queryDriver)
    {
        $this->marshaller = new Marshaller($this);
        $this->queryDriver = $queryDriver;
        $this->nameResolver = new NameResolver();
    }

    // SMELL: should be injected into other objects, and not accessed trhough $em
    public function getQueryDriver()
    {
        return $this->queryDriver;
    }

    // SMELL: should be injected into other objects, and not accessed trhough $em
    public function getNameResolver()
    {
        return $this->nameResolver;
    }

    /**
     * @param string
     * @return Agnostic\Entity\Metadata
     */
    public function getMetadada($entityName)
    {
        if (!isset($this->metadatas[$entityName])) {
            // TODO: call builder

        }
        return $this->metadatas[$entityName];
    }

    /**
     * @param string
     * @return Agnostic\Entity\Repository
     */
    public function getRepository($entityName)
    {
        if (!isset($this->repositories[$entityName])) {
            // TODO: use metadata to determine repository
            $typeName = $this->nameResolver->getTypeName($entityName);
            $className = $this->nameResolver->getRepositoryClassName($typeName);
            $this->repositories[$typeName] = new $className($typeName, $this);            
        }
        return $this->repositories[$typeName];
    }
}
