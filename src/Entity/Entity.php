<?php
namespace Agnostic\Entity;

use Aura\Marshal\Entity\GenericEntity;
use Agnostic\Entity\MetadataBuilder;

class Entity extends GenericEntity
{
    public function toArray()
    {
        $result = [];
        $data = $this->data;

        $entity = $this;

        foreach ($data as $key => $item) {
            if ($item instanceof \Aura\Marshal\Lazy\GenericLazy) {
                $item = $item->get($this);
                if ($item) {
                    $item = $item->toArray();
                }
            }

            $result[$key] = $item;
        }

        return $result;
    }
}
