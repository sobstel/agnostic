<?php
namespace Agnostic\Entity;

use ArrayObject;

class Metadata extends ArrayObject
{
    public function __construct()
    {
        parent::__construct([], ArrayObject::ARRAY_AS_PROPS);
    }
}
