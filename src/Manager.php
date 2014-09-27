<?php
namespace Agnostic;

use Aura\Marshal\Manager as BaseManager;
use Agnostic\Type\Builder as TypeBuilder;
use Agnostic\Relation\Builder as RelationBuilder;
use Agnostic\QueryDriver\Manager as QueryDriverManager;

class Manager extends BaseManager
{
    protected $query_driver_manager;

    public function __construct(array $types = [])
    {
        $this->query_driver_manager = new QueryDriverManager;

        $type_builder = new TypeBuilder($this->query_driver_manager);
        $relation_builder = new RelationBuilder;

        parent::__construct($type_builder, $relation_builder, $types);
    }

    public function getQueryDriverManager()
    {
        return $this->query_driver_manager;
    }

    /**
     * @param string Type name
     * @param string (optional) Query driver name
     * @return \Agnostic\Query\Query
     */
    public function query($name, $query_driver_name = null)
    {
        $query_driver = $query_driver_name ? $this->query_driver_manager->get($query_driver_name) : null;
        return $this->getType($name)->query($query_driver);
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
