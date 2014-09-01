<?php
namespace Agnostic\Query;

use Agnostic\Metadata\EntityMetadata;
use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\Query\Factory as QueryFactory;
use Agnostic\Marshaller;

use IteratorAggregate;
use ArrayIterator;

// proxy over native query
class Query implements IteratorAggregate
{
    protected $nativeQuery;

    protected $entityMetadata;

    protected $queryDriver;

    protected $queryFactory;

    protected $marshaller;

    protected $with = [];

    /**
     * @param object
     */
    public function __construct($nativeQuery, EntityMetadata $entityMetadata, QueryDriverInterface $queryDriver, QueryFactory $queryFactory, Marshaller $marshaller)
    {
        $this->nativeQuery = $nativeQuery;
        $this->entityMetadata = $entityMetadata;
        $this->queryDriver = $queryDriver;
        $this->queryFactory = $queryFactory;
        $this->marshaller = $marshaller;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fetch());
    }

    /**
     * @return mixed
     */
    public function __call($name, $args)
    {
        $result = call_user_func_array([$this->nativeQuery, $name], $args);

        // only nativeQuery should be "decorated"/"proxied"
        // othewise return original query
        if ($result !== $this->nativeQuery) {
            return $result;
        }

        return $this;
    }

    public function findBy($field, array $values)
    {
        $this->queryDriver->addWhereIn($this, $field, $values);

        return $this;
    }

    public function find(array $values)
    {
        $this->findBy($this->entityMetadata['id'], $values);

        return $this;
    }

    public function with($names)
    {
        if (!is_array($names)) {
            $names = [$names];
        }

        $this->with = array_merge($this->with, $names);

        return $this;
    }

    public function fetch(array $opts = [])
    {
        $data = $this->queryDriver->fetchData($this, $opts);

        // marshalize data
        $entityName = $this->entityMetadata['entityName'];
        $ids = $this->marshaller->$entityName->load($data);
        $collection = $this->marshaller->$entityName->getCollection($ids);

        // handle relations
        foreach ($this->with as $name) {
            $this->fetchRelated($collection, $name);
        }

        return $collection;
    }

    public function __invoke(array $opts = [])
    {
        return $this->fetch($opts);
    }

    protected function fetchRelated($collection, $name)
    {
        $relationMetadata = $this->entityMetadata['relations'][$name];

        $ids = $collection->getFieldValues($relationMetadata['id']);

        $targetQuery = $this->queryFactory->create($relationMetadata['targetEntity']);
        
        switch ($relationMetadata['relationship']) {
            case 'BelongsTo':
            case 'HasOne':
            case 'HasMany':
                $targetQuery->findBy($relationMetadata['targetId'], $ids)->fetch();
            break;

            case 'HasManyThrough':
                // @todo
                throw new \Agnostic\Exception('HasManyThrough not supported yet');
            break;
        }
    }
}
