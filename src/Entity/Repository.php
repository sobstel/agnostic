<?php
namespace Agnostic\Entity;

use Agnostic\EntityManager;
use Agnostic\Entity\Metadata;
use Agnostic\Marshaller;
use Agnostic\Query\QueryDriverProxy;

class Repository
{
    protected $metadata;

    protected $queryDriverDecorator;

    public function __construct(Metadata $metadata, QueryDriverProxy $queryDriverProxy)
    {
        $this->metadata = $metadata;
        $this->queryDriverProxy = $queryDriverProxy;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function createBaseQuery()
    {
        return $this->queryDriverProxy->createBaseQuery($this->metadata);
    }

    public function findBy($field, array $values)
    {
        return $this->queryDriverProxy->createFinderQuery($this->metadata, $field, $values);
    }

    public function find(array $values)
    {
        return $this->findBy($this->metadata['id'], $values);
    }
}
