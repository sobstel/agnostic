<?php
namespace Agnostic\Metadata;

use Agnostic\NameResolver;
use Agnostic\Metadata\Metadata;
use Doctrine\Common\Inflector\Inflector;
use ReflectionClass;

class Factory
{
    protected $nameResolver;

    protected $metadatas = [];

    public function __construct(NameResolver $nameResolver)
    {
        $this->nameResolver = $nameResolver;
    }

    /**
     * @return Metadata
     */
    public function get($name)
    {
        if (isset($this->metadatas[$name])) {
            return $this->metadatas[$name];
        }

        $name = Inflector::classify(Inflector::singularize($name));

        if (isset($this->metadatas[$name])) {
            return $this->metadatas[$name];
        }

        return ($this->metadatas[$name] = new Metadata($name, $this->nameResolver));
    }
}
