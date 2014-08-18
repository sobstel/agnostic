<?php
namespace Agnostic\Entity;

class NameResolver
{
    protected $entityNames = [];

    protected $entityNamespaces = [];

    protected $repositoryNamespaces = [];

    public function getEntityClassName($entityName)
    {
        return $this->getClassName($entityName, $this->entityNamespaces, 'Agnostic\Entity\Entity');
    }

    public function getRepositoryClassName($entityName)
    {
        return $this->getClassName($entityName, $this->repositoryNamespaces, 'Agnostic\Entity\Repository');
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

    public function registerRepositoryNamespace($namespace)
    {
        $namespace = rtrim($namespace, '\\').'\\'; // ensure backslash at the end
        $this->repositoryNamespaces[] = $namespace;
    }
}
