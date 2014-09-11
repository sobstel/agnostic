<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\Builder as BaseBuilder;
use Agnostic\Entity\Builder as EntityBuilder;
use Agnostic\Collection\Builder as CollectionBuilder;
use Agnostic\Query\Builder as QueryBuilder;
use Agnostic\QueryDriver\Manager as QueryDriverManager;

class Builder extends BaseBuilder
{
    /*** @var string */
    protected $class = 'Agnostic\Type\Type';

    /*** @var QueryDriverManager */
    protected $query_driver_manager;

    public function __construct(QueryDriverManager $query_driver_manager)
    {
        $this->query_driver_manager = $query_driver_manager;
    }

    /**
     * @return \Agnostic\Type\Type
     */
    public function newInstance($info)
    {
        if (!isset($info['entity_class'])) {
            $info['entity_class'] = 'Agnostic\Entity\Entity';
        }
        $info['entity_builder'] = new EntityBuilder($info['entity_class']);

        if (!isset($info['collection_class'])) {
            $info['collection_class'] = 'Agnostic\Collection\Collection';
        }
        $info['collection_builder'] = new CollectionBuilder($info['collection_class']);

        if (!isset($info['identity_field'])) {
            $info['identity_field'] = 'id';
        }

        $type = parent::newInstance($info);

        // HACK: Aura's builder does not allow custom type object, so replace it dirty way using serializer
        // proper pull request is waiting for approval: https://github.com/auraphp/Aura.Marshal/pull/22
        $type = unserialize(
            str_replace(
                'O:29:"Aura\Marshal\Type\GenericType"',
                sprintf('O:%d:"%s"', strlen($this->class), $this->class),
                serialize($type)
            )
        );

        $type->setName($info['name']);

        if (!isset($info['table_name'])) {
            $info['table_name'] = $info['name'];
        }
        $type->setTableName($info['table_name']);

        if (!isset($info['query_class'])) {
            $info['query_class'] = 'Agnostic\Query\Query';
        }
        $type->setQueryBuilder(new QueryBuilder($info['query_class']));

        if (!isset($info['query_driver'])) {
            $info['query_driver'] = 'default';
        }
        $type->setQueryDriver($this->query_driver_manager->get($info['query_driver']));

        return $type;
    }
}
