<?php
namespace Agnostic\Entity;

use Agnostic\Entity\Metadata;
use Agnostic\Entity\NameResolver;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Annotations\SimpleAnnotationReader;

// annotations definitions need to be required explicitly...
// ... otherwise they're not visible for SimpleAnnotationReader
require_once __DIR__.'/Annotations/Entity.php';
require_once __DIR__.'/Annotations/BelongsTo.php';
require_once __DIR__.'/Annotations/HasMany.php';
require_once __DIR__.'/Annotations/HasManyThrough.php';
require_once __DIR__.'/Annotations/HasOne.php';

class MetadataFactory
{
    protected $nameResolver;

    protected $metadatas = [];

    public function __construct(NameResolver $nameResolver)
    {
        $this->nameResolver = $nameResolver;
    }

    public function get($entityName)
    {
        if (!isset($this->metadatas[$entityName])) {
            $this->create($entityName);
        }

        return $this->metadatas[$entityName];
    }

    protected function create($entityName)
    {
        $metadata = new Metadata;
        $this->metadatas[$entityName] = $metadata;

        $metadata['entityName'] = $entityName;
        $metadata['entityClassName'] = $this->nameResolver->getEntityClassName($entityName);

        $tableizedName = Inflector::tableize($entityName);

        $className = $this->nameResolver->getEntityClassName($entityName);
        $class = new \ReflectionClass($className);

        $reader = new SimpleAnnotationReader();
        $reader->addNamespace('Agnostic\Entity\Annotations');

        $entityAnnotation = $reader->getClassAnnotation($class, 'Agnostic\Entity\Annotations\Entity');

        $metadata['typeName'] = $entityAnnotation->typeName ?: Inflector::pluralize($tableizedName);
        $metadata['id'] = $entityAnnotation->id ?: $tableizedName.'_id';
        $metadata['indexes'] = $entityAnnotation->indexes ?: [];
        $metadata['repositoryClassName'] = $entityAnnotation->repositoryClassName ?: $this->nameResolver->getRepositoryClassName($entityName);

        $metadata['relations'] = [];

        foreach ($reader->getClassAnnotations($class) as $annotation) {
            if (!in_array($annotation->getTag(), ['HasMany', 'BelongsTo', 'HasOne', 'HasManyThrough'])) {
                continue;
            }

            $relation = new Metadata();

            $name = $annotation->name;

            $relation['relationship'] = $annotation->getTag();

            $relation['name'] = $annotation->name;
            $relation['targetEntity'] = $annotation->targetEntity;
            $relation['id'] = $annotation->id ?: $this->get($relation['targetEntity'])['id'];
            $relation['targetTypeName'] = $this->get($annotation->targetEntity)['typeName'];
            $relation['targetId'] = $annotation->targetId ?: $this->get($relation['targetEntity'])['id'];

            if ($relation['relationship'] == 'HasManyThrough') {
                if ($annotation->throughEntity) {
                    $throughType = $this->get($annotation->throughEntity)['typeName'];
                } else {
                    $throughType = $annotation->throughType;
                }

                $relation['throughEntity'] = $annotation->throughEntity;
                $relation['throughType'] = $throughType;
                $relation['throughId'] = $annotation->throughId ?: $relation['id'];
                $relation['throughTargetId'] = $annotation->throughTargetId ?: $this->get($relation['throughEntity'])['id'];
            }

            $metadata['relations'][] = $relation;
        }

        return $metadata;
    }
}
