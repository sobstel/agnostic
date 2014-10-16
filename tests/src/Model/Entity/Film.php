<?php
namespace Agnostic\Tests\Model\Entity;

use Agnostic\Entity\EntityTrait;

class Film implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use EntityTrait;
}
