<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\GenericType;
use Agnostic\Type\Metadata;
use Agnostic\QueryDriver\QueryDriverInterface;
use IteratorAggregate;
use ArrayIterator;

class Type extends GenericType
{
    protected $name;

    /*** @var QueryDriverInterface */
    protected $query_driver;

    protected $metadata;

    /*** @var object */
    protected $query;

    protected $opts = [];

    public function __construct($name, QueryDriverInterface $query_driver)
    {
        $this->name = $name;
        $this->metadata = new Metadata($this);

        $this->query_driver = $query_driver;
        $this->query = $this->query_driver->createQuery($this->metadata->getTableName());

        parent::__construct([]);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getQueryDriver()
    {
        return $this->query_driver;
    }

    public function getRelation($name)
    {
        return parent::getRelation($name);
    }

    public function getEntityByField($field, $value)
    {
        return parent::getEntityByField($field, $value);
    }

    public function getIdentityField()
    {
        return $this->identity_field ?: $this->metadata->getIdentityField();
    }

    /***/

    public function findBy($field, array $values)
    {
        $this->query_driver->addWhereIn($this, $field, $values);

        return $this;
    }

    public function find(array $values)
    {
        $this->findBy($this->getIdentityField(), $values);

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

        $ids = $this->load($data);

        $collection = $this->getCollection($ids);

        // foreach ($this->relatedNames as $relatedName) {
        //     var_dump($type->getRelation($relatedName));
        //     exit;
        //     // $type->getRelation($relatedName)->fetchByCollection($collection);

        //     var_dump($this->metadata->getRelationInfo($relatedName));
        //     exit;


            // @todo: getRelation()->
            // $type->load($relatedName, $collection);
            // var_dump($this->metadata->getRelationInfo($relatedName));exit;
            // var_dump($this->marshalManager->getType($name)->getRelation());exit;
            //
        // }

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
