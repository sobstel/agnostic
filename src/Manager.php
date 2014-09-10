<?php
namespace Agnostic;

use Aura\Marshal\Manager as BaseManager;
use Agnostic\Type\Builder as TypeBuilder;
use Agnostic\Relation\Builder as RelationBuilder;
use Agnostic\Metadata\Factory as MetadataFactory;

class Manager extends BaseManager
{
    public function __construct(TypeBuilder $type_builder, RelationBuilder $relation_builder = null, array $types = [])
    {
        if (!$relation_builder) {
            $relation_builder = new RelationBuilder;
        }

        parent::__construct($type_builder, $relation_builder, $types);
    }

    public function __get($name)
    {
        return $this->getType($name);
    }

    public function get($name)
    {
        return $this->getType($name);
    }

    public function getType($name)
    {
        // @todo: normalize name with Inflector

        if (!isset($this->types[$name])) {
            $this->buildType($name);
        }

        return parent::__get($name);
    }

    protected function buildType($name)
    {
        // @todo: normalize name with Inflector

        if (!isset($this->types[$name])) {
            $this->types[$name] = [];
        }

        $this->types[$name]['name'] = $name;

        return parent::buildType($name);
    }

    /**
     * @deprecated
     */
    public function setTypeByEntity(EntityMetadata $metadata)
    {
        $entityName = $metadata['entityName'];

        // $this->setType(
        //     $entityName,
        //     [
        //         'identity_field' => $metadata['id'],
        //         'index_fields' => $metadata['indexes'],
        //         'entity_class_name' => $metadata['entityClassName']
        //     ]
        // );

        foreach ($metadata["relations"] as $relationMetadata) {
            $baseInfo = [
                'native_field' => $relationMetadata['id'],
                'foreign_type' => $relationMetadata['targetEntity'],
                'foreign_field' => $relationMetadata['targetId']
            ];

            switch ($relationMetadata['relationship']) {
                case 'HasMany':
                    $this->setRelation($entityName, $relationMetadata['name'], array_merge($baseInfo, ['relationship' => 'has_many']));
                break;

                case 'BelongsTo':
                    $this->setRelation($entityName, $relationMetadata['name'], array_merge($baseInfo, ['relationship' => 'belongs_to']));
                break;

                case 'HasOne':
                    $this->setRelation($entityName, $relationMetadata['name'], array_merge($baseInfo, ['relationship' => 'has_one']));
                break;

                case 'HasManyThrough':
                    $this->setRelation(
                        $entityName,
                        $relationMetadata['name'],
                        array_merge(
                            $baseInfo,
                            [
                                'relationship' => 'has_many_through',
                                'through_type' => $relationMetadata['throughEntity'],
                                'through_native_field' => $relationMetadata['throughId'],
                                'through_foreign_field' => $relationMetadata['throughTargetId'],
                            ]
                        )
                    );
                break;
            }
        }
    }
}
