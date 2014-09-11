<?php
namespace Agnostic\Collection;

use Aura\Marshal\Collection\BuilderInterface;

class Builder implements BuilderInterface
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @return Agnostic\Collection\Collection
     */
    public function newInstance(array $data)
    {
        $class = $this->class;
        return new $class($data);
    }
}
