<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\GenericType;
use Agnostic\Query\Builder as QueryBuilder;
use Agnostic\QueryDriver\QueryDriverInterface;

class Type extends GenericType
{
    protected $name;

    protected $table_name;

    protected $query_builder;

    protected $query_driver;

    public function __construct()
    {
        parent::__construct();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTableName($table_name)
    {
        $this->table_name = $table_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function setQueryBuilder(QueryBuilder $query_builder)
    {
        $this->query_builder = $query_builder;
    }

    public function setQueryDriver(QueryDriverInterface $query_driver)
    {
        $this->query_driver = $query_driver;
    }

    public function addIndexField($index_field)
    {
        if (!isset($this->index_fields[$index_field])) {
            $this->index_fields[$index_field] = [];
        }
    }

    /**
     * {@inheritdoc}
     *
     * @todo SMELL almost copied from parent
     */
    public function getCollection(array $identity_values)
    {
        $list = [];
        foreach ($identity_values as $identity_value) {
            if (isset($this->index_identity[$identity_value])) {
                $offset = $this->index_identity[$identity_value];
                $list[] =& $this->data[$offset];
            }
        }
        return $this->collection_builder->newInstance($list);
    }

    public function getRelation($name)
    {
        if (!isset($this->relations[$name])) {
            throw new \Agnostic\Exception\Exception(sprintf('Undefined relation "%s" for type "%s"', $name, $this->getName()));
        }
        return parent::getRelation($name);
    }

    public function query()
    {
        $info = [
            'type' => $this,
            'query_driver' => $this->query_driver,
        ];
        return $this->query_builder->newInstance($info);
    }
}
