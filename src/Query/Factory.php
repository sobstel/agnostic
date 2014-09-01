<?php
namespace Agnostic\Query;

use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\NameResolver;
use Agnostic\Marshaller;
use Agnostic\Metadata\Factory as MetadataFactory;

class Factory
{
    protected $queryDriver;

    protected $marshaller;

    protected $metadataFactory;

    public function __construct(QueryDriverInterface $queryDriver, NameResolver $nameResolver = null)
    {
        $this->queryDriver = $queryDriver;  

        if (!$nameResolver) {
            $nameResolver = new NameResolver();
        }

        $this->marshaller = new Marshaller($nameResolver);
        $this->metadataFactory = new MetadataFactory($nameResolver, $this->marshaller);
    }

    public function create($entityName)
    {
        $metadata = $this->metadataFactory->get($entityName); 
        $className = $metadata['queryClassName'];

        $nativeQuery = $this->queryDriver->createNativeQuery($metadata['tableName']);
        $query = new $className($nativeQuery, $metadata, $this->queryDriver, $this, $this->marshaller);

        return $query;
    }
}
