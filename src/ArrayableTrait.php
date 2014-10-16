<?php
namespace Agnostic;

use Aura\Marshal\Lazy\LazyInterface;

trait ArrayableTrait
{
    public function toArray()
    {
        $array = [];

        foreach ($this as $k => $v) {
            if ($v instanceof LazyInterface) {
                $v = $this->offsetGet($k);
            }
            if (is_object($v) && method_exists($v, 'toArray')) {
                $v = $v->toArray();
            }
            $array[$k] = $v;
        }

        return $array;
    }
}
