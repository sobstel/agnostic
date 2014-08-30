<?php
namespace Agnostic\Entity;

use Agnostic\Entity\MetadataFactory;
use Agnostic\Query\QueryDriverInterface;

class RepositoryFactory
{
    protected $metadataFactory;

    protected $queryDriver;

    protected $repositories = [];

    public function __construct(MetadataFactory $metadataFactory, QueryDriverInterface $queryDriver)
    {
        $this->metadataFactory = $metadataFactory;
        $this->queryDriver = $queryDriver;
    }

    public function get($entityName)
    {
        if (!isset($this->repositories[$entityName])) {
            $metadata = $this->metadataFactory->get($entityName);
            $className = $metadata['repositoryClassName'];

            $this->repositories[$entityName] = new $className($metadata, $this->queryDriver);
        }

        return $this->repositories[$entityName];
    }
}
