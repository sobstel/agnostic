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

    protected $related = [];

    protected $opts = [];

    public function __construct(Type $type, QueryDriverInterface $query_driver)
    {
        $this->type = $type;
        $this->query_driver = $query_driver;

        $this->query = $query_driver->createQuery($type->getTableName());
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

    public function with($name, callable $query_callback = null)
    {
        if (!is_array($name)) {
            $query = $this->type->getRelation($name)->getForeign()->query();

            if ($query_callback) {
                $query_callback($query);
            }

            $this->related[$name] = $query;

            return $this;
        }

        // convenience call, eg. ['events', 'teamA', 'teamB', 'round' => ['season' => 'competition']]
        $spec = $name;
        foreach ($spec as $k => $v) {
            if (is_int($k)) {
                $this->with($v);
            } else {
                $query = $this->type->getRelation($k)->getForeign()->query();
                $query->with($v);

                $this->related[$k] = $query;
            }
        }

        return $this;
    }

    public function opts(array $opts)
    {
        $this->opts = array_merge($this->opts, $opts);
    }

    public function fetch(array $opts = [])
    {
        $opts = array_merge($this->opts, $opts);
        $data = $this->query_driver->fetchData($this->query, $opts);
        $ids = $this->type->load($data);
        $collection = $this->type->getCollection($ids);
        $this->fetchRelated($collection);
        return $collection;
    }

    public function fetchRelated($collection)
    {
        foreach ($this->related as $name => $query) {
            $relation = $this->type->getRelation($name);
            $relationship = $relation->getRelationship();

            if (in_array($relationship, ['belongs_to', 'has_many', 'has_one'])) {
                $values = array_unique($collection->getFieldValues($relation->getNativeField()));
                $query
                    ->findBy($relation->getForeignField(), $values)
                    ->fetch();
            }

            // @todo: has_many_through
        }
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
