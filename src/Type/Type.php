<?php
namespace Agnostic\Type;

use Aura\Marshal\Type\GenericType;

class Type extends GenericType
{
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
