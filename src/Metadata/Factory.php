<?php
namespace Agnostic\Metadata;

use Agnostic\Marshal\Manager as MarshalManager;
use Agnostic\NameResolver;
use Agnostic\Metadata\EntityMetadata;
use Agnostic\Metadata\RelationMetadata;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Annotations\SimpleAnnotationReader;

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

    protected $marshalManager;

    protected $metadatas = [];

    public function __construct(NameResolver $nameResolver, MarshalManager $marshalManager)
    {
        $this->nameResolver = $nameResolver;
        $this->marshalManager = $marshalManager;
    }

    public function get($entityName)
    {
        if (isset($this->metadatas[$entityName])) {
            return $this->metadatas[$entityName];
        }

        $metadata = new EntityMetadata;
        $this->metadatas[$entityName] = $metadata;

        $metadata['entityName'] = $entityName;
        $metadata['entityClassName'] = $this->nameResolver->getEntityClassName($entityName);

        $className = $this->nameResolver->getEntityClassName($entityName);
        $class = new \ReflectionClass($className);

        $reader = new SimpleAnnotationReader();
        $reader->addNamespace('Agnostic\Metadata\Annotations');

        $entityAnnotation = $reader->getClassAnnotation($class, 'Agnostic\Metadata\Annotations\Entity');

        $metadata['tableName'] = $entityAnnotation->tableName ?: Inflector::pluralize(Inflector::tableize($entityName));
        $metadata['id'] = $entityAnnotation->id ?: sprintf('%s_id', Inflector::singularize($metadata['tableName']));
        $metadata['indexes'] = $entityAnnotation->indexes ?: [];
        $metadata['queryClassName'] = $entityAnnotation->queryClassName ?: $this->nameResolver->getQueryClassName($entityName);

        $metadata['relations'] = [];

        foreach ($reader->getClassAnnotations($class) as $annotation) {
            if (!in_array($annotation->getTag(), ['HasMany', 'BelongsTo', 'HasOne', 'HasManyThrough'])) {
                continue;
            }

            $relation = new RelationMetadata;

            $relation['relationship'] = $annotation->getTag();
            $relation['name'] = $annotation->name;
            $relation['targetEntity'] = $annotation->targetEntity;

            // load
            $this->get($relation['targetEntity']);

            if ($relation['relationship'] == 'BelongsTo') {
                $relation['id'] = $annotation->id ?: $this->get($relation['targetEntity'])['id']; 

                if ($annotation->targetId) {
                    $relation['targetId'] = $annotation->targetId;
                } else {
                    $relation['targetId'] = $this->get($relation['targetEntity'])['id'];

                    if ($relation['targetId'] == 'id') {
                        $relation['targetId'] = sprintf('%s_id', Inflector::singularize($this->get($relation['targetEntity'])['tableName']));
                    }
                }
            }

            if ($relation['relationship'] == 'HasOne' || $relation['relationship'] == 'HasMany') {
                $relation['id'] = $annotation->id ?: $metadata['id'];

                if ($annotation->targetId) {
                    $relation['targetId'] = $annotation->targetId;
                } else {
                    $relation['targetId'] = $metadata['id'];

                    if ($relation['targetId'] == 'id') {
                        $relation['targetId'] = sprintf('%s_id', $metadata['tableName']);
                    }
                }
            }

            if ($relation['relationship'] == 'HasManyThrough') {
                // @todo
                throw new \Agnostic\Exception('HasManyThrough not supported yet');
                // $relation['id'] = $annotation->id ?: $metadata['id'];
                // $relation['targetId'] = $this->get($relation['targetEntity'])['id']; 
                // $relation['throughEntity'] = $annotation->throughEntity;
                // $relation['throughId'] = $annotation->throughId ?: $relation['id'];
                // $relation['throughTargetId'] = $annotation->throughTargetId ?: $this->get($relation['throughEntity'])['id'];
            }

            $metadata['relations'][$relation['name']] = $relation;
        }

        $this->marshalManager->setTypeByEntity($metadata);

        return $metadata;
    }
}
