<?php
namespace Agnostic;

class NameResolver
{
    protected $entityNames = [];

    protected $entityPrefixes = [];

    protected $queryPrefixes = [];

    public function getEntityClassName($entityName)
    {
        return $this->getClassName($entityName, $this->entityPrefixes, 'Agnostic\Entity\Entity');
    }

    public function getQueryClassName($entityName)
    {
        return $this->getClassName($entityName, $this->queryPrefixes, 'Agnostic\Query\Query');
    }

    protected function getClassName($name, array $prefixes, $defaultClassname)
    {
        foreach ($prefixes as $prefix) {
            $className = $prefix.$name;

            if (class_exists($className, true)) {
                return $className;
            }
        }

        return $defaultClassname;
    }

    public function registerEntityPrefix($prefix)
    {
        $prefix = rtrim($prefix, '\\').'\\'; // ensure backslash at the end
        $this->entityPrefixes[] = $prefix;
    }

    public function registerQueryPrefix($prefix)
    {
        $prefix = rtrim($prefix, '\\').'\\'; // ensure backslash at the end
        $this->queryPrefixes[] = $prefix;
    }
}
