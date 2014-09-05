<?php
namespace Agnostic\Query;

use Agnostic\Metadata\Metadata;
use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\Query\Factory as QueryFactory;
use Agnostic\Marshal\Manager as MarshalManager;
use Agnostic\Query\Info;
use Agnostic\Query\QueryStack;

use IteratorAggregate;
use ArrayIterator;

class Query implements IteratorAggregate
{
    /*** @var object */
    protected $nativeQuery;

    protected $metadata;

    protected $queryDriver;

    protected $queryFactory;

    protected $marshalManager;

    protected $queryStack;

    protected $opts = [];

    /**
     * @param object
     */
    public function __construct($nativeQuery, Metadata $metadata, QueryDriverInterface $queryDriver, QueryFactory $queryFactory, MarshalManager $marshalManager)
    {
        $this->nativeQuery = $nativeQuery;
        $this->metadata = $metadata;
        $this->queryDriver = $queryDriver;
        $this->queryFactory = $queryFactory;
        $this->marshalManager = $marshalManager;
        $this->queryStack = new QueryStack($this);
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
        $this->findBy($this->metadata->getIdentityField(), $values);

        return $this;
    }

    /**
     * @param mixed
     * @return Query
     */
    public function with($spec)
    {
        if (is_array($spec)) {
            $names = $spec;
        } else {
            $names = [$spec];
        }

        foreach ($names as $k => $v) {
            $isNestedSpec = (!is_int($k));

            $name = $isNestedSpec ? $k : $v;

            $relationMetadata = $this->metadata['relations'][$name];
            $targetQuery = $this->queryFactory->create($relationMetadata['targetEntity']);
            $this->queryStack->add($name, $targetQuery);

            if ($isNestedSpec) {
                $targetQuery->with($v);
            }
        }

        return $this;
    }

    public function opts(array $opts)
    {
        $this->opts = array_merge($this->opts, $opts);
    }

    // flush
    public function fetch(array $opts = [])
    {
        $opts = array_merge($this->opts, $opts);

        $data = $this->queryDriver->fetchData($this, $opts);

        // marshalize data
        $name = $this->metadata->getName();
        $this->marshalManager->setType(
            $name,
            [
                'identity_field' => $this->metadata->getIdentityField(),
                'entity_class_name' => $this->metadata->getEntityClassName()
            ]
        );
        $ids = $this->marshalManager->$name->load($data);

        $collection = $this->marshalManager->$name->getCollection($ids);

        // $this->fetchRelated($collection);

        return $collection;
    }

    public function __invoke(array $opts = [])
    {
        return $this->fetch($opts);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fetch());
    }

    public function __toString()
    {
        return $this->nativeQuery->__toString();
    }

    // public function fetchRelated($collection)
    // {
    //     $this->queryStack->fetch($collection);
    // }
}
