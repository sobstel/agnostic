<?php
namespace Agnostic;

use Aura\Marshal\Manager as BaseMarshaller;
use Agnostic\Type\Builder as TypeBuilder;
use Aura\Marshal\Relation\Builder as RelationBuilder;
use Agnostic\NameResolver;
use Agnostic\Metadata\EntityMetadata;

class Marshaller extends BaseMarshaller
{
    protected $nameResolver;

    public function __construct(NameResolver $nameResolver)
    {    
        parent::__construct(new TypeBuilder, new RelationBuilder);

        $this->nameResolver = $nameResolver;
    }

    public function setTypeByEntity(EntityMetadata $entityMetadata)
    {
        $typeName = $entityMetadata['typeName'];

        if (isset($this->types[$typeName])) {
            return ;
        }

        $this->setType(
            $typeName,
            [
                'identity_field' => $entityMetadata['id'],
                'index_fields' => $entityMetadata['indexes'],
                'entity_class_name' => $entityMetadata['entityClassName']
            ]
        );

        foreach ($entityMetadata["relations"] as $relationMetadata) {
            $baseInfo = [
                'native_field' => $relationMetadata['id'],
                'foreign_type' => $relationMetadata['targetType'],
                'foreign_field' => $relationMetadata['targetId']
            ];

            switch ($relationMetadata['relationship']) {
                case 'HasMany':
                    $this->setRelation($typeName, $relationMetadata['name'], array_merge($baseInfo, ['relationship' => 'has_many']));
                break;

                case 'BelongsTo':
                    $this->setRelation($typeName, $relationMetadata['name'], array_merge($baseInfo, ['relationship' => 'belongs_to']));
                break;

                case 'HasOne':
                    $this->setRelation($typeName, $relationMetadata['name'], array_merge($baseInfo, ['relationship' => 'has_one']));
                break;

                case 'HasManyThrough':
                    $this->setRelation(
                        $typeName,
                        $relationMetadata['name'],
                        array_merge(
                            $baseInfo,
                            [
                                'relationship' => 'has_many_through',
                                'through_type' => $relationMetadata['throughType'],
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
