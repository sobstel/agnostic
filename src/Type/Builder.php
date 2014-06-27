<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\Builder as BaseTypeBuilder;
use Agnostic\Collection\Builder as CollectionBuilder;
use Agnostic\Entity\Builder as EntityBuilder;
use Aura\Marshal\Lazy\Builder as LazyBuilder;

class Builder extends BaseTypeBuilder
{
    public function newInstance($info)
    {
        $info['collection_builder'] = new CollectionBuilder;
        $info['entity_builder'] = new EntityBuilder($info['entity_class_name']);
        $info['lazy_builder'] = new LazyBuilder;

        return parent::newInstance($info);
    }
}
