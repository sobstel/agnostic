<?php
namespace Agnostic\Entity;

use Aura\Marshal\Entity\MagicArrayAccessTrait;

class Entity implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use EntityTrait;
}
