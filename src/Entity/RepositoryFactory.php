<?php
namespace Agnostic\Entity;

use Agnostic\Query\QueryDriverInterface;
use Agnostic\Entity\NameResolver;
use Agnostic\Query\QueryDriverProxy;
use Agnostic\Marshaller;
use Agnostic\Entity\MetadataFactory;

class RepositoryFactory
{
    protected $metadataFactory;

    protected $queryDriverProxy;

    protected $repositories = [];

    public function __construct(QueryDriverInterface $queryDriver, NameResolver $nameResolver = null)
    {
        if (!$nameResolver) {
            $nameResolver = new NameResolver();
        }

        $marshaller = new Marshaller($nameResolver);

        $this->metadataFactory = new MetadataFactory($nameResolver, $marshaller);
        $this->queryDriverProxy = new QueryDriverProxy($queryDriver, $marshaller, $this);
    }

    public function get($entityName)
    {
        if (!isset($this->repositories[$entityName])) {
            $metadata = $this->metadataFactory->get($entityName);
            $className = $metadata['repositoryClassName'];

            $this->repositories[$entityName] = new $className($metadata, $this->queryDriverProxy);
        }

        return $this->repositories[$entityName];
    }
}
