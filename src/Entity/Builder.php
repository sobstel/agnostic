<?php
namespace Agnostic\Entity;

use Aura\Marshal\Entity\BuilderInterface;
use Agnostic\Manager;

class Builder implements BuilderInterface
{
    protected $class = 'Agnostic\Entity\Entity';

    public function __construct($class = null)
    {
        if ($class) {
            $this->setClass($class);
        }
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function newInstance(array $data)
    {
        $class = $this->class;
        $entity = new $class;

        foreach ($data as $field => $value) {
            $entity->offsetSet($field, $value);
        }

        return $entity;
    }
}
