<?php
namespace Agnostic\Query;

use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\NameResolver;
use Agnostic\Marshal\Manager as MarshalManager;
use Agnostic\Metadata\Factory as MetadataFactory;

class Factory
{
    protected $queryDriver;

    protected $marshalManager;

    protected $nameResolver;

    protected $metadataFactory;

    public function __construct(QueryDriverInterface $queryDriver, NameResolver $nameResolver = null)
    {
        $this->queryDriver = $queryDriver;

        if (!$nameResolver) {
            $nameResolver = new NameResolver();
        }

        $this->nameResolver = $nameResolver;
        $this->metadataFactory = new MetadataFactory($nameResolver);
        $this->marshalManager = new MarshalManager($this->metadataFactory);
    }

    public function create($name)
    {
        $class = $this->nameResolver->getQueryClassName($name);
        $metadata = $this->metadataFactory->get($name);

        $nativeQuery = $this->queryDriver->createNativeQuery($metadata->getTableName());
        $query = new $class($nativeQuery, $metadata, $this->queryDriver, $this, $this->marshalManager);

        return $query;
    }
}
