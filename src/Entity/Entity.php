<?php
namespace Agnostic\Entity;

class Entity implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use EntityTrait;
}
