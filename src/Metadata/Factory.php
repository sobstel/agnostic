<?php
namespace Agnostic\Metadata;

use Agnostic\NameResolver;
use Agnostic\Metadata\Metadata;
use Doctrine\Common\Inflector\Inflector;
use ReflectionClass;

// annotations definitions need to be required explicitly...
// ... otherwise they're not visible for SimpleAnnotationReader
require_once __DIR__.'/Annotations/Entity.php';
require_once __DIR__.'/Annotations/BelongsTo.php';
require_once __DIR__.'/Annotations/HasMany.php';
require_once __DIR__.'/Annotations/HasManyThrough.php';
require_once __DIR__.'/Annotations/HasOne.php';

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
