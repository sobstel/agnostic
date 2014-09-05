<?php
namespace Agnostic\Metadata;

use Agnostic\NameResolver;
use Agnostic\Metadata\Annotation\Query as QueryAnnotation;
use Agnostic\Query\Query;
use ReflectionClass;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Annotations\SimpleAnnotationReader as AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Metadata
{
    static protected $isAnnotationLoaderRegistered = false;

    protected $name;

    protected $nameResolver;

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

    public function __construct($name, NameResolver $nameResolver)
    {
        self::registerAnnotationLoader();

        $this->name = $name;
        $this->nameResolver = $nameResolver;

        $this->refClass = new ReflectionClass($this->nameResolver->getQueryClassName($name));

        $annotationReader = new AnnotationReader;
        $annotationReader->addNamespace('Agnostic\Metadata\Annotation');
        $this->annotationReader = $annotationReader;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEntityName()
    {
        return $this->getQueryAnnotation()->entity ?: $this->name;
    }

    public function getEntityClassName()
    {
        return $this->nameResolver->getEntityClassName($this->getEntityName());
    }

    public function getTableName()
    {
        return $this->getQueryAnnotation()->table ?: Inflector::pluralize(Inflector::tableize($this->name));
    }

    public function getIdentityField()
    {
        return $this->getQueryAnnotation()->identityField ?: sprintf('%s_id', Inflector::singularize($this->getTableName()));
    }

    protected function getQueryAnnotation()
    {
        $queryAnnotations = $this->getClassAnnotations('Query');

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

    //     foreach ($reader->getClassAnnotations($class) as $annotation) {
    //         if (!in_array($annotation->getTag(), ['HasMany', 'BelongsTo', 'HasOne', 'HasManyThrough'])) {
    //             continue;
    //         }

    //         $relation = new RelationMetadata;

    //         $relation['relationship'] = $annotation->getTag();
    //         $relation['name'] = $annotation->name;
    //         $relation['targetEntity'] = $annotation->targetEntity;

    //         // load
    //         $this->get($relation['targetEntity']);

    //         if ($relation['relationship'] == 'BelongsTo') {
    //             $relation['id'] = $annotation->id ?: $this->get($relation['targetEntity'])['id'];

    //             if ($annotation->targetId) {
    //                 $relation['targetId'] = $annotation->targetId;
    //             } else {
    //                 $relation['targetId'] = $this->get($relation['targetEntity'])['id'];

    //                 if ($relation['targetId'] == 'id') {
    //                     $relation['targetId'] = sprintf('%s_id', Inflector::singularize($this->get($relation['targetEntity'])['tableName']));
    //                 }
    //             }
    //         }

    //         if ($relation['relationship'] == 'HasOne' || $relation['relationship'] == 'HasMany') {
    //             $relation['id'] = $annotation->id ?: $metadata['id'];

    //             if ($annotation->targetId) {
    //                 $relation['targetId'] = $annotation->targetId;
    //             } else {
    //                 $relation['targetId'] = $metadata['id'];

    //                 if ($relation['targetId'] == 'id') {
    //                     $relation['targetId'] = sprintf('%s_id', $metadata['tableName']);
    //                 }
    //             }
    //         }

    //         if ($relation['relationship'] == 'HasManyThrough') {
    //             // @todo
    //             throw new \Agnostic\Exception('HasManyThrough not supported yet');
    //             // $relation['id'] = $annotation->id ?: $metadata['id'];
    //             // $relation['targetId'] = $this->get($relation['targetEntity'])['id'];
    //             // $relation['throughEntity'] = $annotation->throughEntity;
    //             // $relation['throughId'] = $annotation->throughId ?: $relation['id'];
    //             // $relation['throughTargetId'] = $annotation->throughTargetId ?: $this->get($relation['throughEntity'])['id'];
    //         }

    //         $metadata['relations'][$relation['name']] = $relation;
    //     }

    //     return $metadata;
    // }

}
