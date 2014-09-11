<?php
namespace Agnostic;

use Aura\Marshal\Manager as BaseManager;
use Agnostic\Type\Builder as TypeBuilder;
use Agnostic\Relation\Builder as RelationBuilder;
use Agnostic\QueryDriver\QueryDriverInterface;

class Manager extends BaseManager
{
    public function __construct(QueryDriverInterface $query_driver)
    {
        $type_builder = new TypeBuilder($query_driver);
        $relation_builder = new RelationBuilder;

        parent::__construct($type_builder, $relation_builder);
    }

    /**
     * @return Query
     */
    public function query($name)
    {
        return $this->getType($name)->query();
    }

    public function __get($name)
    {
        if (!isset($this->types[$name])) {
            $this->setType($name, []);
        }
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
