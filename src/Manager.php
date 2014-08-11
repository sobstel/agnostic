<?php
namespace Agnostic;

use Aura\Marshal\Manager as BaseManager;
use Agnostic\Type\Builder as TypeBuilder;
use Agnostic\Relation\Builder as RelationBuilder;
use Agnostic\Entity\Metadata as EntityMetadata;
use Doctrine\Common\Annotations\SimpleAnnotationReader as AnnotationReader;
use Doctrine\Common\Inflector\Inflector;

require_once __DIR__.'/Entity/Annotations/Entity.php';
require_once __DIR__.'/Entity/Annotations/BelongsTo.php';
require_once __DIR__.'/Entity/Annotations/HasMany.php';
require_once __DIR__.'/Entity/Annotations/HasManyThrough.php';
require_once __DIR__.'/Entity/Annotations/HasOne.php';

class Manager extends BaseManager
{
    protected $entityTypeNames = [];

    protected $entityNamespaces = [];

    public function __construct()
    {
        parent::__construct(new TypeBuilder($this), new RelationBuilder($this));
    }

    public function __get($name)
    {
        // load type from entity on the fly
        if (!isset($this->types[$name])) {
            $entityName = Inflector::classify(Inflector::singularize($name));
            $this->setTypeByEntity($entityName);
        }

        return parent::__get($name);
    }

    // set Aura type using Entity class
    public function setTypeByEntity($entityName)
    {
        $typeName = $this->getEntityTypeName($entityName);

        if (isset($this->types[$typeName])) {
            return ;
        }

        $tableizedName = Inflector::tableize($entityName);

        $className = $this->getEntityClassName($entityName);
        $class = new \ReflectionClass($className);

        $reader = new AnnotationReader();
        $reader->addNamespace('Agnostic\Entity\Annotations');

        $entityAnnotation = $reader->getClassAnnotation($class, 'Agnostic\Entity\Annotations\Entity');

        $this->setType(
            $typeName,
            [
                'identity_field' => $entityAnnotation->id ?: $tableizedName.'_id',
                'index_fields' => $entityAnnotation->indexes ?: [],
                'entity_class_name' => $className,
            ]
        );

        // relations
        foreach ($reader->getClassAnnotations($class) as $annotation) {
            if (!in_array($annotation->getTag(), ['HasMany', 'BelongsTo', 'HasOne', 'HasManyThrough'])) {
                continue;
            }

            $name = $annotation->name;
            $targetTypeName = $this->getEntityTypeName($annotation->targetEntity);
            $nativeField = $annotation->id ?: $this->__get($typeName)->getIdentityField();

            $baseInfo = [
                'native_field' => $nativeField,
                'foreign_type' => $targetTypeName,
                'foreign_field' => $annotation->targetId ?: $this->__get($targetTypeName)->getIdentityField(),
            ];

            switch ($annotation->getTag()) {
                case 'HasMany':
                    $this->setRelation($typeName, $name, array_merge($baseInfo, ['relationship' => 'has_many']));
                break;

                case 'BelongsTo':
                    $this->setRelation($typeName, $name, array_merge($baseInfo, ['relationship' => 'belongs_to']));
                break;

                case 'HasOne':
                    $this->setRelation($typeName, $name, array_merge($baseInfo, ['relationship' => 'has_one']));
                break;

                case 'HasManyThrough':
                    if ($annotation->throughEntity) {
                        $throughType = $this->getEntityTypeName($annotation->throughEntity);
                    } else {
                        $throughType = $annotation->throughType;
                    }

                    $this->setRelation(
                        $typeName,
                        $name,
                        array_merge(
                            $baseInfo,
                            [
                                'relationship' => 'has_many_through',
                                'through_type' => $throughType,
                                'through_native_field' => $annotation->throughId ?: $nativeField,
                                'through_foreign_field' => $annotation->throughTargetId ?: $this->$targetTypeName->getIdentityField(),
                            ]
                        )
                    );
                break;
            }
        }
    }

    protected function getEntityTypeName($entityName)
    {
        if (!isset($this->entityTypeNames[$entityName])) {
            $typeName = Inflector::pluralize(Inflector::tableize($entityName));
            $this->entityTypeNames[$entityName] = $typeName;
        }

        return $this->entityTypeNames[$entityName];
    }

    public function registerEntityNamespace($namespace)
    {
        $namespace = rtrim($namespace, '\\').'\\'; // ensure backslash at the end

        $this->entityNamespaces[] = $namespace;
    }

    protected function getEntityClassName($entityName)
    {
        foreach ($this->entityNamespaces as $namespace) {
            $className = $namespace.$entityName;

            if (class_exists($className, true)) {
                return $className;
            }
        }

        return 'Agnostic\Entity\GenericEntity'; // default
    }
}
