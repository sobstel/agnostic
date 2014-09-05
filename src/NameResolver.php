<?php
namespace Agnostic;

use Doctrine\Common\Inflector\Inflector;

class NameResolver
{
    /*** @var string[] */
    protected $queryPrefixes = [];

    /*** @var string[] */
    protected $entityPrefixes = [];

    public function __construct($prefix = null)
    {
        if ($prefix !== null) {
            $this->registerPrefix($prefix);
        }
    }

    public function registerPrefix($prefix)
    {
        $this->registerQueryPrefix($prefix);
        $this->registerEntityPrefix($prefix);
    }

    public function registerQueryPrefix($prefix)
    {
        $this->queryPrefixes[] = $prefix;
    }

    public function registerEntityPrefix($prefix)
    {
        $this->entityPrefixes[] = $prefix;
    }

    public function getQueryClassName($name)
    {
        return $this->getClassName(sprintf('%sQuery', $name), $this->queryPrefixes, 'Agnostic\Query\Query');
    }

    public function getEntityClassName($name)
    {
        return $this->getClassName($name, $this->entityPrefixes, 'Agnostic\Entity\Entity');
    }

    protected function getClassName($name, array $prefixes, $defaultClassname)
    {
        $name = Inflector::classify($name);

        foreach ($prefixes as $prefix) {
            $prefix = rtrim($prefix, '\\').'\\'; // ensure backslash at the end
            $className = $prefix.$name;

            if (class_exists($className, true)) {
                return $className;
            }
        }

        return $defaultClassname;
    }
}
