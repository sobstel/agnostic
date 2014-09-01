<?php
namespace Agnostic\Entity;

use Aura\Marshal\Entity\GenericEntity;
use Agnostic\Entity\MetadataBuilder;

/**
 * @Entity
 */
class Entity extends GenericEntity
{
    public function toArray()
    {
        $data = $this->data;

        $data = array_filter($data, function($val){
            if ($val instanceof \Aura\Marshal\Lazy\GenericLazy) {
                return false;
            }
            return true;
        });

        return $data;
    }
}
