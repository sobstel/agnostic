<?php
namespace Agnostic\Entity;

use Doctrine\Common\Inflector\Inflector;

class NameResolver
{
    protected $entityNames = [];

    protected $entityNamespaces = [];

    protected $repositoryNamespaces = [];

    // public function getTypeName($entityName)
    // {
    //     if ($this->isTypeName($entityName)) { // normalize name
    //         $entityName = $this->getEntityName($entityName);
    //     }

    //     $typeName = array_search($entityName, $this->entityNames);

    //     if ($typeName === false) {
    //         $typeName = Inflector::pluralize(Inflector::tableize($entityName));
    //         $this->entityNames[$typeName] = $entityName;
    //     }

    //     return $typeName;
    // }

    // public function getEntityName($typeName)
    // {
    //     // if (!$this->isTypeName($typeName)) { // normalize name
    //     //     $typeName = $this->getTypeName($typeName);
    //     // }

    //     if (!isset($this->entityNames[$typeName])) {
    //         $entityName = Inflector::classify(Inflector::singularize($typeName));
    //         $this->entityNames[$typeName] = $entityName;
    //     }

    //     return $this->entityNames[$typeName];
    // }

    // protected function isTypeName($name)
    // {
    //     return ($name == strtolower($name));
    // }

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
