<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\Builder as BaseBuilder;
use Agnostic\Entity\Builder as EntityBuilder;
// use Doctrine\Common\Inflector\Inflector;

class Builder extends BaseBuilder
{
    protected $class = 'Agnostic\Type\Type';
    /**
     * @return \Agnostic\Type\Type
     */
    public function newInstance($info)
    {
        if (!isset($info['entity_class'])) {
            $entity_class = 'Agnostic\Entity\Entity';
        }
        $info['entity_builder'] = new EntityBuilder($entity_class);

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

        return $type;
    }
}
