<?php
namespace Agnostic\Tests\Model\Entity;

use Agnostic\Entity\EntityTrait;

class City implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use EntityTrait;
}
