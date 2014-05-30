<?php
namespace Agnostic\Entity;

use Aura\Marshal\Entity\Builder as BaseEntityBuilder; # TODO: remove, use implements BuilderInterface
use Agnostic\Manager;

class Builder extends BaseEntityBuilder
{
    public function __construct($class)
    {
        $this->class = $class;
    }
}
