<?php
namespace Agnostic\Query;

use IteratorAggregate;
use Agnostic\Entity\Metadata;
use Agnostic\Query\QueryDriverProxy;
use Agnostic\Marshaller;
use ArrayIterator;
use Agnostic\Entity\RepositoryFactory;

class QueryProxy implements IteratorAggregate
{
    protected $query;

    protected $metadata;

    protected $queryDriverProxy;

    protected $marshaller;

    protected $repositoryFactory;

    protected $with = [];

    /**
     * @param object
     */
    public function __construct($query, Metadata $metadata, QueryDriverProxy $queryDriverProxy, Marshaller $marshaller, RepositoryFactory $repositoryFactory)
    {
        $this->query = $query;
        $this->metadata = $metadata;
        $this->queryDriverProxy = $queryDriverProxy;
        $this->marshaller = $marshaller;
        $this->repositoryFactory = $repositoryFactory;
    }

    /**
     * @return QueryProxy
     */
    public function __call($name, $args)
    {
        call_user_func_array([$this->query, $name], $args);
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fetch());
    }

    public function with($name)
    {
        $this->with[] = $name;

        return $this;
    }

    public function fetch(array $opts = [])
    {
        $data = $this->queryDriverProxy->fetchData($this, $opts);

        // marshalize data
        $typeName = $this->metadata['typeName'];
        $ids = $this->marshaller->$typeName->load($data);
        $collection = $this->marshaller->$typeName->getCollection($ids);

        // handle relations
        foreach ($this->with as $name) {
            $this->fetchRelated($collection, $name); // @todo ids
        }

        return $collection;
    }

    public function __invoke(array $opts = [])
    {
        return $this->fetch($opts);
    }

    protected function fetchRelated($collection, $name)
    {
        $relationMetadata = $this->metadata['relations'][$name];

        $ids = $collection->getFieldValues($relationMetadata['id']);

        $targetRepository = $this->repositoryFactory->get($relationMetadata['targetEntity']);
        
        switch ($relationMetadata['relationship']) {
            case 'BelongsTo':
            case 'HasOne':
            case 'HasMany':
                $targetRepository->findBy($relationMetadata['targetId'], $ids)->fetch();
            break;

            case 'HasManyThrough':
                // @todo
            break;
        }
    }
}
