<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\Builder as BaseBuilder;
use Agnostic\Collection\Builder as CollectionBuilder;
use Agnostic\Entity\Builder as EntityBuilder;
use Agnostic\Lazy\Builder as LazyBuilder;

class Builder extends BaseBuilder
{
    protected $class = 'Agnostic\Type\Type';

    /**
     * @return \Agnostic\Type\Type
     */
    public function newInstance($info)
    {
        $info['collection_builder'] = new CollectionBuilder;
        $info['entity_builder'] = new EntityBuilder($info['entity_class_name']);
        $info['lazy_builder'] = new LazyBuilder;

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

        return $type;
    }
}
