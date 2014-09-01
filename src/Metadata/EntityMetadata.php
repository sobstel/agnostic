<?php
namespace Agnostic\Metadata;

use ArrayObject;

class EntityMetadata extends ArrayObject
{
    public function __construct()
    {
        parent::__construct([], ArrayObject::ARRAY_AS_PROPS);
    }
}
