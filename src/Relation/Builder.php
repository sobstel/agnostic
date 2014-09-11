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

    protected function prepNative(&$info, BaseManager $manager)
    {
        if (!isset($info['native_field'])) {
            $type = $manager->getType($info['type']);
            $foreign_type = $manager->getType($info['foreign_type']);

            if ($info['relationship'] == 'belongs_to') {
                $info['native_field'] = sprintf('%s_id', Inflector::singularize($foreign_type->getTableName()));
            }

            if (in_array($info['relationship'], ['has_many', 'has_one', 'has_many_through'])) {
                $info['native_field'] = $type->getIdentityField();
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

            if (in_array($info['relationship'], ['has_many', 'has_one', 'has_many_through'])) {
                $info['foreign_field'] = sprintf('%s_id', Inflector::singularize($foreign_type->getTableName()));
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

        $type = $manager->getType($info['type']);
        $foreign_type = $manager->getType($info['foreign_type']);

        if (!isset($info['through_type'])) {
            $info['through_type'] = sprintf('%s_%s', $type->getName(), $foreign_type->getName());
        }

        if (!isset($info['through_native_field'])) {
            $info['through_native_field'] = $info['native_field'];
        }

        if (!isset($info['through_foreign_field'])) {
            $info['through_foreign_field'] = $info['foreign_field'];
        }

        parent::prepThrough($info, $manager);
    }
}
