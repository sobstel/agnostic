<?php
namespace Agnostic;

use Agnostic\Marshaller;
use AGnostic\QueryDriver\QueryDriverInterface;

class EntityManager
{
    protected $marshaller;

    protected $queryDriver;

    public function __construct(QueryDriverInterface $queryDriver)
    {
        $this->queryDriver = $queryDriver;
        $this->marshaller = new Marshaller;
    }

    public function registerEntityNamespace($namespace)
    {
        $this->marshaller->registerEntityNamespace($namespace);
    }

    public function registerRepositoryNamespace($namespace)
    {
        $this->marshaller->registerRepositoryNamespace($namespace);
    }

    public function getQueryDriver()
    {
        return $this->queryDriver;
    }

    public function getRepository($entityName)
    {
        $className = $this->marshaller->getRepositoryClassName($entityName);
        $typeName = $this->marshaller->getEntityTypeName($entityName);
        $repository = new $className($typeName, $this);
        return $repository;
    }
}
