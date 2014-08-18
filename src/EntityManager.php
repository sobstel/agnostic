<?php
namespace Agnostic;

use Agnostic\Marshaller;
use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\Entity\NameResolver;
use Agnostic\Entity\Metadata;
use Agnostic\Entity\MetadataFactory;

// GOD class
class EntityManager
{
    protected $marshaller;

    protected $queryDriver;

    protected $nameResolver;

    protected $repositories;

    public function __construct(QueryDriverInterface $queryDriver, NameResolver $nameResolver = null)
    {
        $this->queryDriver = $queryDriver;
        $this->nameResolver = $nameResolver ?: new NameResolver();

        $this->metadataFactory = new MetadataFactory($this->nameResolver);
        $this->marshaller = new Marshaller($this->nameResolver, $this->metadataFactory);
    }

    /**
     * @param string
     * @return Agnostic\Entity\Metadata
     */
    public function getMetadada($entityName)
    {
        if (!isset($this->metadatas[$entityName])) {
            $this->metadatas[$entityName] = $this->metadataFactory->get($entityName);
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
            $metadata = $this->getMetadada($entityName);
            $className = $metadata['repositoryClassName'];
            $this->repositories[$entityName] = new $className($metadata['typeName'], $this->queryDriver);            
        }
        return $this->repositories[$entityName];
    }
}
