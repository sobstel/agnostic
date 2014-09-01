<?php
namespace Agnostic;

class NameResolver
{
    protected $entityNames = [];

    protected $entityNamespaces = [];

    protected $queryNamespaces = [];

    public function getEntityClassName($entityName)
    {
        return $this->getClassName($entityName, $this->entityNamespaces, 'Agnostic\Entity\Entity');
    }

    public function getQueryClassName($entityName)
    {
        return $this->getClassName($entityName, $this->queryNamespaces, 'Agnostic\Query\Query');
    }

    protected function getClassName($name, array $namespaces, $defaultClassname)
    {
        foreach ($namespaces as $namespace) {
            $className = $namespace.$name;

            if (class_exists($className, true)) {
                return $className;
            }
        }

        return $defaultClassname;
    }

    public function registerEntityNamespace($namespace)
    {
        $namespace = rtrim($namespace, '\\').'\\'; // ensure backslash at the end
        $this->entityNamespaces[] = $namespace;
    }

    public function registerQueryNamespace($namespace)
    {
        $namespace = rtrim($namespace, '\\').'\\'; // ensure backslash at the end
        $this->queryNamespaces[] = $namespace;
    }
}
