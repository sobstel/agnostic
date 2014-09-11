<?php
namespace Agnostic\Collection;

use Aura\Marshal\Collection\GenericCollection;

class Collection extends GenericCollection
{
    // resolves lazy stuff
    public function dump()
    {
        $func_iterate = function ($col) use (&$func_iterate) {
            if (is_array($col) || ($col instanceof \Traversable)) {
                foreach ($col as $val) {
                    $func_iterate($val);
                }
            }
        };
        $func_iterate($this);

        return $this;
    }
}
