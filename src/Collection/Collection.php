<?php
namespace Agnostic\Collection;

use Aura\Marshal\Collection\GenericCollection;

class Collection extends GenericCollection
{
    public function toArray()
    {
        $result = [];

        foreach ($this->getIterator() as $item) {
            $result[] = $item->toArray();
        }

        return $result;
    }
}
