<?php
namespace Agnostic\Type;

use Agnostic\Metadata\Annotation\Query as QueryAnnotation;
use Agnostic\Query\Query;
use ReflectionClass;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Annotations\SimpleAnnotationReader as AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Metadata
{
    static protected $isAnnotationLoaderRegistered = false;

    protected $type;

    /*** @var ReflectionClass */
    protected $refClass;

    /*** @var AnnotationReader */
    protected $annotationReader;

    protected $classAnnotations;

    // HACK: AnnotationReader does not use native autoloader by default
    static public function registerAnnotationLoader()
    {
        if (!static::$isAnnotationLoaderRegistered) {
            AnnotationRegistry::registerLoader(function($class){
                spl_autoload_call($class);
                return class_exists($class, false);
            });
            static::$isAnnotationLoaderRegistered = true;
        }
    }

    public function __construct(Type $type)
    {
        self::registerAnnotationLoader();

        $this->type = $type;
        $this->refClass = new ReflectionClass($type);

        $annotationReader = new AnnotationReader;
        $annotationReader->addNamespace('Agnostic\Type\Annotation');
        $this->annotationReader = $annotationReader;
    }

    public function getName()
    {
        return $this->type->getName();
    }

    public function getEntityName()
    {
        return $this->getTypeAnnotation()->entity ?: $this->getName();
    }

    public function getEntityClassName()
    {
        return $this->nameResolver->getEntityClassName($this->getEntityName());
    }

    public function getTableName()
    {
        return $this->getTypeAnnotation()->table ?: Inflector::pluralize(Inflector::tableize($this->name));
    }

    public function getIdentityField()
    {
        return $this->getTypeAnnotation()->identityField ?: sprintf('%s_id', Inflector::singularize($this->getTableName()));
    }

    public function getRelationInfo($name)
    {
        foreach ($this->getClassAnnotations('BelongsTo') as $annotation) {
            // if (!in_array($annotation->getTag(), ['HasMany', 'BelongsTo', 'HasOne', 'HasManyThrough'])) {
            //     continue;
            // }

            $relationInfo = [
                'relationship' => $annotation->getTag(),
                'name' => $annotation->name,
                'query' => $annotation->query,
                'native_field' => $annotation->nativeField,
                'foreign_field' => $annotation->foreignField,
            ];

            return $relationInfo;


            // $relationInfo['query'] = $annotation->;

            // if ($relation['relationship'] == 'BelongsTo') {
            //     $relation['id'] = $annotation->id ?: $this->get($relation['targetEntity'])['id'];

            //     if ($annotation->targetId) {
            //         $relation['targetId'] = $annotation->targetId;
            //     } else {
            //         $relation['targetId'] = $this->get($relation['targetEntity'])['id'];

            //         if ($relation['targetId'] == 'id') {
            //             $relation['targetId'] = sprintf('%s_id', Inflector::singularize($this->get($relation['targetEntity'])['tableName']));
            //         }
            //     }
            // }

            // if ($relation['relationship'] == 'HasOne' || $relation['relationship'] == 'HasMany') {
            //     $relation['id'] = $annotation->id ?: $metadata['id'];

            //     if ($annotation->targetId) {
            //         $relation['targetId'] = $annotation->targetId;
            //     } else {
            //         $relation['targetId'] = $metadata['id'];

            //         if ($relation['targetId'] == 'id') {
            //             $relation['targetId'] = sprintf('%s_id', $metadata['tableName']);
            //         }
            //     }
            // }

            // if ($relation['relationship'] == 'HasManyThrough') {
            //     // @todo
            //     throw new \Agnostic\Exception('HasManyThrough not supported yet');
            //     // $relation['id'] = $annotation->id ?: $metadata['id'];
            //     // $relation['targetId'] = $this->get($relation['targetEntity'])['id'];
            //     // $relation['throughEntity'] = $annotation->throughEntity;
            //     // $relation['throughId'] = $annotation->throughId ?: $relation['id'];
            //     // $relation['throughTargetId'] = $annotation->throughTargetId ?: $this->get($relation['throughEntity'])['id'];
            // }

            $metadata['relations'][$relation['name']] = $relation;
        }
    }

    protected function getTypeAnnotation()
    {
        $queryAnnotations = $this->getClassAnnotations('Type');

        if (empty($queryAnnotations)) {
            return new QueryAnnotation([]);
        }

        return reset($queryAnnotations); // only first one matters
    }

    protected function getClassAnnotations($tag = null)
    {
        $this->readClassAnnotations();

        if ($tag) {
            return (isset($this->classAnnotations[$tag]) ? $this->classAnnotations[$tag] : []);
        }

        return $this->classAnnotations;
    }

    protected function readClassAnnotations()
    {
        if ($this->classAnnotations !== null) {
            return ;
        }

        $this->classAnnotations = [];
        $annotations = $this->annotationReader->getClassAnnotations($this->refClass);

        foreach ($annotations as $annotation) {
            $key = $annotation->getTag();

            if (!isset($this->classAnnotations[$key])) {
                $this->classAnnotations[$key] = [];
            }

            $this->classAnnotations[$key][] = $annotation;
        }
    }

    //    $metadata = new EntityMetadata;
    //     $this->metadatas[$entityName] = $metadata;

    //     $metadata['entityName'] = $entityName;
    //     $metadata['entityClassName'] = ;

    //     $className = $this->nameResolver->getEntityClassName($entityName);
    //     $class = ($className);

    //     $reader = new SimpleAnnotationReader();
    //     $reader->addNamespace('Agnostic\Metadata\Annotations');

    //     $entityAnnotation = $reader->getClassAnnotation($class, 'Agnostic\Metadata\Annotations\Entity');

    //     $metadata['tableName'] = $entityAnnotation->tableName ?: Inflector::pluralize(Inflector::tableize($entityName));
    //     $metadata['id'] = $entityAnnotation->id ?: ;
    //     $metadata['indexes'] = $entityAnnotation->indexes ?: [];
    //     $metadata['queryClassName'] = $entityAnnotation->queryClassName ?: $this->nameResolver->getQueryClassName($entityName);

    //     $metadata['relations'] = [];



    //     return $metadata;
    // }

}
