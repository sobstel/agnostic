<?php
namespace Agnostic\Query;

use IteratorAggregate;
use Agnostic\Query\QueryDriverInterface;
use Agnostic\Marshaller;
use ArrayIterator;

class QueryDecorator implements IteratorAggregate
{
    protected $innerQuery;

    /*** @var QueryDriverInterface Inner (non-decorated) query driver */
    protected $queryDriver;

    protected $marshaller;

    protected $typeName;

    protected $related = [];

    /**
     * @param object
     */
    public function __construct($innerQuery, $typeName, QueryDriverInterface $queryDriver, Marshaller $marshaller)
    {
        $this->innerQuery = $innerQuery;
        $this->typeName = $typeName;
        $this->queryDriver = $queryDriver;
        $this->marshaller = $marshaller;
    }

    public function __call($name, $args)
    {
        return call_user_func_array([$this->innerQuery, $name], $args);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fetch());
    }

    public function with($name)
    {
        $this->related[] = $name;

        return $this;
    }

    public function fetch(array $opts = [])
    {
        $data = $this->queryDriver->fetchData($this->innerQuery, $opts);

        $typeName = $this->typeName;

        $ids = $this->marshaller->$typeName->load($data);
        $collection = $this->marshaller->$typeName->getCollection($ids);

        // @todo fetch related (with())

        return $collection;
    }
}
