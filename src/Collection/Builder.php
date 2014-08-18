<?php
namespace Agnostic\Collection;

use Aura\Marshal\Collection\Builder as BaseCollectionBuilder;
use Agnostic\Collection\Collection;

class Builder extends BaseCollectionBuilder
{
    public function newInstance(array $data)
    {
        return new Collection($data);
    }
}
