<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\Builder as BaseBuilder;
use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\Entity\Builder as EntityBuilder;
use Agnostic\Collection\Builder as CollectionBuilder;
use Agnostic\Lazy\Builder as LazyBuilder;

class Builder extends BaseBuilder
{
    protected $class_prefixes = [];

    protected $default_query_driver;

    protected $default_class = 'Agnostic\Type\Type';

    public function __construct(QueryDriverInterface $default_query_driver, $default_class = null)
    {
        $this->default_query_driver = $default_query_driver;

        if ($default_class) {
            $this->default_class = $default_class;
        }
    }

    /**
     * @return \Agnostic\Type\Type
     */
    public function newInstance($info)
    {
        $class = $this->getClass($info['name']);

        if (!isset($info['query_driver'])) {
            $info['query_driver'] = $this->default_query_driver;
        }

        $type = new $class($info['name'], $info['query_driver']);

        $base = ['identity_field' => null, 'index_fields'=> []];
        $info = array_merge($base, $info);

        if (!isset($info['entity_builder'])) {
            $entity_class = substr($class, 0, -4);
            $info['entity_builder'] = new EntityBuilder($entity_class);
        }

        if (!isset($info['collection_builder'])) {
            $info['collection_builder'] = new CollectionBuilder;
        }

        if (!isset($info['lazy_builder'])) {
            $info['lazy_builder'] = new LazyBuilder;
        }

        $type->setIdentityField($info['identity_field']);
        $type->setIndexFields($info['index_fields']);
        $type->setEntityBuilder($info['entity_builder']);
        $type->setCollectionBuilder($info['collection_builder']);
        $type->setLazyBuilder($info['lazy_builder']);

        return $type;
    }

    public function registerClassPrefix($class_prefix)
    {
        $this->class_prefixes[] = $class_prefix;
    }

    protected function getClass($name)
    {
        foreach (array_merge($this->class_prefixes, ['']) as $prefix) {
            if (!empty($prefix)) {
                $prefix = rtrim($prefix, '\\').'\\'; // ensure backslash at the end
                $class = $prefix.$name.'Type';
            }

            if (class_exists($class, true)) {
                return $class;
            }
        }

        return $this->defaultClass;
    }
}
