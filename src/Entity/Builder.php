<?php
namespace Agnostic\Entity;

use Aura\Marshal\Entity\BuilderInterface;
use Agnostic\Manager;

class Builder implements BuilderInterface
{
    protected $className = 'Agnostic\Entity\Entity';

    public function __construct($className = null)
    {
        if ($className) {
            $this->className = $className;
        }
    }

    public function newInstance(array $data)
    {
        $className = $this->className;
        $entity = new $className;

        foreach ($data as $field => $value) {
            $entity->offsetSet($field, $value);
        }

        return $entity;
    }
}
