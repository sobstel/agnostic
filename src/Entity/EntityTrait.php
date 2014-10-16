<?php
namespace Agnostic\Entity;

use Agnostic\ArrayableTrait;
use Agnostic\Exception\MissingFieldException;
use Aura\Marshal\Entity\MagicArrayAccessTrait;
use Aura\Marshal\Lazy\LazyInterface;

trait EntityTrait
{
    use MagicArrayAccessTrait;
    use ArrayableTrait;

    private $data = [];

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function offsetGet($key)
    {
        if (!$this->offsetExists($key)) {
            throw new MissingFieldException(sprintf('Entity "%s" has no value loaded for field "%s"', get_class($this), $key));
        }

        $value = $this->data[$key];

        if ($value instanceof LazyInterface) {
            $value = $value->get($this);
            $this->offsetSet($key, $value);
        }

        return $value;
    }

    public function offsetSet($key, $val)
    {
        $this->data[$key] = $val;
    }

    public function offsetUnset($key)
    {
        unset($this->data[$key]);
    }

    public function count()
    {
        return count($this->data);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
