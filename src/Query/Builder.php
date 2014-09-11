<?php
namespace Agnostic\Query;

class Builder
{
    protected $default_class = 'Agnostic\Query\Query';

    public function __construct($class = null)
    {
        if ($class) {
            $this->class = $class;
        }
    }

    /**
     * @return Agnostic\Query\Query
     */
    public function newInstance(array $info)
    {
        $class = $this->class;
        return new $class($info['type'], $info['query_driver']);
    }
}
