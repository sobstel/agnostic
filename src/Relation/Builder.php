<?php
namespace Agnostic\Relation;

use Aura\Marshal\Relation\Builder as BaseBuilder;
use Aura\Marshal\Manager as BaseManager;
use Doctrine\Common\Inflector\Inflector;

class Builder extends BaseBuilder
{
    protected $relationship_class = [
        'belongs_to' => 'Agnostic\Relation\BelongsTo',
        'has_one' => 'Agnostic\Relation\HasOne',
        'has_many' => 'Agnostic\Relation\HasMany',
        'has_many_through' => 'Agnostic\Relation\HasManyThrough',
    ];

    protected function prepRelationship(&$info, BaseManager $manager)
    {
        if (!isset($info['relationship'])) {
            if ($info['name'] == Inflector::singularize($info['name'])) { // singular: guess belongs_to
                $info['relationship'] = 'belongs_to';
            } elseif ($info['name'] == Inflector::pluralize($info['name'])) { // plural: guess has_many
                $info['relationship'] = 'has_many';
            }
        }

        parent::prepRelationship($info, $manager);
    }

    protected function prepNative(&$info, BaseManager $manager)
    {
        if (!isset($info['native_field'])) {
            $type = $manager->getType($info['type']);
            $foreign_type = $manager->getType($info['foreign_type']);

            if ($info['relationship'] == 'belongs_to') {
                $info['native_field'] = sprintf('%s_id', Inflector::singularize($foreign_type->getTableName()));
            }

            if ($info['relationship'] == 'has_many' || $info['relationship'] == 'has_one') {
                $info['native_field'] = $type->getIdentityField();
            }

            if ($info['relationship'] == 'has_many_through') {
                // @todo
            }
        }

        parent::prepNative($info, $manager);
    }

    protected function prepForeign(&$info, BaseManager $manager)
    {
        if (!isset($info['foreign_field'])) {
            $type = $manager->getType($info['type']);
            $foreign_type = $manager->getType($info['foreign_type']);

            if ($info['relationship'] == 'belongs_to') {
                $info['foreign_field'] = $foreign_type->getIdentityField();
            }

            if ($info['relationship'] == 'has_many' || $info['relationship'] == 'has_one') {
                $info['foreign_field'] = sprintf('%s_id', Inflector::singularize($type->getTableName()));
            }

            if ($info['relationship'] == 'has_many_through') {
                // @todo
            }
        }

        parent::prepForeign($info, $manager);
    }

    protected function prepThrough(&$info, BaseManager $manager)
    {
        if ($info['relationship'] != 'has_many_through') {
            $info['through'] = null;
            return;
        }

        parent::prepThrough($info, $manager);
    }
}
