<?php
namespace Agnostic;

use Agnostic\Marshaller;
use Agnostic\Entity\Metadata;
use Agnostic\Entity\MetadataFactory;
use Agnostic\Entity\NameResolver;
use Agnostic\Query\QueryDriverInterface;
use Agnostic\Query\QueryDriverDecorator;

// GOD class
class EntityManager
{
    protected $marshaller;

    protected $queryDriver;

    protected $nameResolver;

    protected $repositories;

    public function __construct(QueryDriverInterface $queryDriver, NameResolver $nameResolver = null)
    {
        $this->queryDriver = new QueryDriverDecorator($queryDriver);
        $this->nameResolver = $nameResolver ?: new NameResolver();
        $this->marshaller = new Marshaller($this->nameResolver);
        $this->metadataFactory = new MetadataFactory($this->nameResolver, $this->marshaller);
    }

    /**
     * @param string
     * @return Agnostic\Entity\Repository
     */
    public function getRepository($entityName)
    {
        if (!isset($this->repositories[$entityName])) {
            $metadata = $this->metadataFactory->get($entityName);
            $className = $metadata['repositoryClassName'];

            $this->repositories[$entityName] = new $className($metadata, $this->queryDriver, $this->marshaller);
        }

        return $this->repositories[$entityName];
    }
}
