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

    protected $metadataFactory;

    public function __construct(QueryDriverInterface $queryDriver, NameResolver $nameResolver = null)
    {
        $this->queryDriver = $queryDriver;  

        if (!$nameResolver) {
            $nameResolver = new NameResolver();
        }

        $this->marshalManager = new MarshalManager($nameResolver);
        $this->metadataFactory = new MetadataFactory($nameResolver, $this->marshalManager);
    }

    public function create($entityName)
    {
        $metadata = $this->metadataFactory->get($entityName); 
        $className = $metadata['queryClassName'];

        $nativeQuery = $this->queryDriver->createNativeQuery($metadata['tableName']);
        $query = new $className($nativeQuery, $metadata, $this->queryDriver, $this, $this->marshalManager);

        return $query;
    }
}
