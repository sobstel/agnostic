<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\Builder as BaseBuilder;
use Agnostic\Entity\Builder as EntityBuilder;
use Agnostic\Query\Builder as QueryBuilder;
use Agnostic\QueryDriver\QueryDriverInterface;

class Builder extends BaseBuilder
{
    protected $class = 'Agnostic\Type\Type';

    protected $default_query_driver;

    public function __construct(QueryDriverInterface $query_driver)
    {
        $this->default_query_driver = $query_driver;
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

        $type->setQueryDriver($this->default_query_driver);

        return $type;
    }
}
