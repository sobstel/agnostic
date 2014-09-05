<?php
namespace Agnostic\Marshal;

use Aura\Marshal\Manager as BaseManager;
use Agnostic\Type\Builder as TypeBuilder;
use Agnostic\Relation\Builder as RelationBuilder;
use Agnostic\Metadata\Factory as MetadataFactory;

class Manager extends BaseManager
{
    protected $metadataFactory;

    public function __construct(MetadataFactory $metadataFactory)
    {
        parent::__construct(new TypeBuilder, new RelationBuilder);
        $this->metadataFactory = $metadataFactory;
    }

    public function __get($name)
    {
        return $this->getType($name);


        return parent::__get($name);
    }

    public function getType($name)
    {
        if (!isset($this->types[$name])) {
            $metadata = $this->metadataFactory->get($name);

            // load on-the-fly
            $this->marshalManager->setType(
                $name,
                [
                    'identity_field' => $metadata->getIdentityField(),
                    'entity_class_name' => $metadata->getEntityClassName()
                ]
            );
        }

        return parent::__get($name);
    }

    public function setTypeByEntity(EntityMetadata $metadata)
    {
        $entityName = $metadata['entityName'];

        $this->setType(
            $entityName,
            [
                'identity_field' => $metadata['id'],
                'index_fields' => $metadata['indexes'],
                'entity_class_name' => $metadata['entityClassName']
            ]
        );

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
