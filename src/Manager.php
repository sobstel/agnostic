<?php
namespace Agnostic;

use Aura\Marshal\Manager as BaseManager;
use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\Type\Builder as TypeBuilder;
use Agnostic\Relation\Builder as RelationBuilder;
use Agnostic\Config\Reader as ConfigReader;

use Agnostic\Query\Query;

class Manager extends BaseManager
{
    protected $query_driver;

    public function __construct(QueryDriverInterface $query_driver)
    {
        $type_builder = new TypeBuilder;
        $relation_builder = new RelationBuilder;

        parent::__construct($type_builder, $relation_builder);

        $this->query_driver = $query_driver;
    }

    public function query($name)
    {
        $query = new Query($this->getType($name), $this->query_driver);
        return $query;
    }


    public function __get($name)
    {
        // @todo: lazy loaded
        return parent::__get($name);
    }

    public function getType($name)
    {
        return $this->__get($name);
    }

    protected function buildType($name)
    {
        $this->types[$name]['name'] = $name;
        return parent::buildType($name);
    }
}
