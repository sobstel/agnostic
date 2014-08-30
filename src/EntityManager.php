<?php
namespace Agnostic;

use Agnostic\Marshaller;
use Agnostic\Entity\Metadata;
use Agnostic\Entity\MetadataFactory;
use Agnostic\Entity\NameResolver;
use Agnostic\Query\QueryDriverInterface;
use Agnostic\Query\QueryDriverDecorator;
use Agnostic\Entity\RepositoryFactory;

// GOD class
class EntityManager
{
    protected $repositoryFactory;

    public function __construct(QueryDriverInterface $queryDriver, NameResolver $nameResolver = null)
    {
        if (!$nameResolver) {
            $nameResolver = new NameResolver();
        }

        $marshaller = new Marshaller($nameResolver);
        $queryDriver = new QueryDriverDecorator($queryDriver, $marshaller);
        $metadataFactory = new MetadataFactory($nameResolver, $marshaller);
        $repositoryFactory = new RepositoryFactory($metadataFactory, $queryDriver);

        $this->repositoryFactory = $repositoryFactory;
    }

    // convenience alias for getRepository
    public function get($entityName)
    {
        return $this->getRepository($entityName);
    }

    /**
     * @param string
     * @return Agnostic\Entity\Repository
     */
    public function getRepository($entityName)
    {
        return $this->repositoryFactory->get($entityName);
    }
}
