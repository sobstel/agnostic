<?php
namespace Agnostic\Marshal;

use Aura\Marshal\Manager as BaseManager;
use Agnostic\Type\Builder as TypeBuilder;
use Aura\Marshal\Relation\Builder as RelationBuilder;
use Agnostic\NameResolver;
use Agnostic\Metadata\EntityMetadata;

class Manager extends BaseManager
{
    protected $nameResolver;

    public function __construct(NameResolver $nameResolver)
    {    
        parent::__construct(new TypeBuilder, new RelationBuilder);

        $this->nameResolver = $nameResolver;
    }

    public function setTypeByEntity(EntityMetadata $entityMetadata)
    {
        $entityName = $entityMetadata['entityName'];

        $this->setType(
            $entityName,
            [
                'identity_field' => $entityMetadata['id'],
                'index_fields' => $entityMetadata['indexes'],
                'entity_class_name' => $entityMetadata['entityClassName']
            ]
        );

        foreach ($entityMetadata["relations"] as $relationMetadata) {
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
