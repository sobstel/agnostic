<?php
namespace Agnostic\Metadata;

use ArrayObject;

class RelationMetadata extends ArrayObject
{
    public function __construct()
    {
        parent::__construct([], ArrayObject::ARRAY_AS_PROPS);
    }
}
