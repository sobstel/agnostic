<?php
namespace Agnostic\Entity;

use Aura\Marshal\Entity\GenericEntity;
use Aura\Marshal\Lazy\LazyInterface;
use Agnostic\Exception\MissingFieldException;

class Entity extends GenericEntity
{
    // original GenerictEntity misses "use Aura\Marshal\Lazy\LazyInterface;",
    // so check for LazyInterface is never met
    public function offsetGet($field)
    {
        if (!$this->offsetExists($field)) {
            throw new MissingFieldException(sprintf('Entity "%s" has no value loaded for field "%s"', get_class($this), $field));
        }

        $value = $this->data[$field];

        if ($value instanceof LazyInterface) {
            $value = $value->get($this);
            $this->offsetSet($field, $value);
        }

        return $value;
    }
}
