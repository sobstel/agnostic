<?php
namespace Agnostic\Collection;

use Aura\Marshal\Collection\Builder as BaseCollectionBuilder;

class Builder extends BaseCollectionBuilder
{
    public function newInstance(array $data)
    {
        return new GenericCollection($data);
    }
}
