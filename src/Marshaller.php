<?php
namespace Agnostic;

use Aura\Marshal\Manager as BaseMarshaller;
use Agnostic\Type\Builder as TypeBuilder;
use Aura\Marshal\Relation\Builder as RelationBuilder;
use Agnostic\Entity\NameResolver;
use Agnostic\Entity\Metadata;

class Marshaller extends BaseMarshaller
{
    protected $nameResolver;

    public function __construct(NameResolver $nameResolver)
    {    
        parent::__construct(new TypeBuilder, new RelationBuilder);

        $this->nameResolver = $nameResolver;
    }

    public function setTypeByEntity(Metadata $metadata)
    {
        $typeName = $metadata['typeName'];

        if (isset($this->types[$typeName])) {
            return ;
        }

        $this->setType(
            $typeName,
            [
                'identity_field' => $metadata['id'],
                'index_fields' => $metadata['indexes'],
                'entity_class_name' => $metadata['entityClassName']
            ]
        );

        foreach ($metadata["relations"] as $relation) {
            $baseInfo = [
                'native_field' => $relation['id'],
                'foreign_type' => $relation['targetTypeName'],
                'foreign_field' => $relation['targetId']
            ];

            switch ($relation['relationship']) {
                case 'HasMany':
                    $this->setRelation($typeName, $relation['name'], array_merge($baseInfo, ['relationship' => 'has_many']));
                break;

                case 'BelongsTo':
                    $this->setRelation($typeName, $relation['name'], array_merge($baseInfo, ['relationship' => 'belongs_to']));
                break;

                case 'HasOne':
                    $this->setRelation($typeName, $relation['name'], array_merge($baseInfo, ['relationship' => 'has_one']));
                break;

                case 'HasManyThrough':
                    $this->setRelation(
                        $typeName,
                        $relation['name'],
                        array_merge(
                            $baseInfo,
                            [
                                'relationship' => 'has_many_through',
                                'through_type' => $relation['throughType'],
                                'through_native_field' => $relation['throughId'],
                                'through_foreign_field' => $relation['throughTargetId'],
                            ]
                        )
                    );
                break;
            }
        }
    }
}
