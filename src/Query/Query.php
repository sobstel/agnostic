<?php
namespace Agnostic\Query;

use Agnostic\QueryDriver\QueryDriverInterface;
use IteratorAggregate;
use ArrayIterator;
use Doctrine\Common\Inflector\Inflector;

use Agnostic\Type\Type;

class Query
{
    protected $type;

    /*** @var QueryDriverInterface */
    protected $query_driver;

    protected $table_name;

    /*** @var object */
    protected $query;

    protected $opts = [];

    public function __construct(Type $type, QueryDriverInterface $query_driver)
    {
        $this->type = $type;
        $this->query_driver = $query_driver;

        $this->query = $query_driver->createQuery($this->getTableName());
    }

    public function setTableName($table_name)
    {
        $this->table_name = $table_name;
    }

    public function getTableName()
    {
        if (!$this->table_name) {
            $this->table_name = $this->type->getName();
        }

        return $this->table_name;
    }

    public function findBy($field, array $values)
    {
        $this->query_driver->addWhereIn($this, $field, $values);

        return $this;
    }

    public function find(array $values)
    {
        $this->findBy($this->type->getIdentityField(), $values);

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
        $data = $this->query_driver->fetchData($this->query, $opts);
        $ids = $this->type->load($data);
        $collection = $this->type->getCollection($ids);
        return $collection;
    }

    public function __call($name, $args)
    {
        $result = call_user_func_array([$this->query, $name], $args);

        // only query should be "decorated"/"proxied"
        // othewise return original query
        if ($result !== $this->query) {
            return $result;
        }

        return $this;
    }

    public function __toString()
    {
        return $this->query->__toString();
    }

    public function __invoke(array $opts = [])
    {
        return $this->fetch($opts);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fetch());
    }
}
