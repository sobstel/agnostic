<?php
namespace Agnostic\Query;

use Agnostic\Query\QueryDriverInterface;

class QueryDecorator
{
    protected $innerQuery;

    /*** @var QueryDriverInterface Inner (non-decorated) query driver */
    protected $queryDriver;

    /**
     * @param object
     */
    public function __construct($innerQuery, QueryDriverInterface $queryDriver)
    {
        $this->innerQuery = $innerQuery;
        $this->queryDriver = $queryDriver;
    }

    public function __call($name, $args)
    {
        return call_user_func_array([$this->innerQuery, $name], $args);
    }

    /**
     * @param mixed
     */
    public function loadRelated($scopeName)
    {
        // TODO: if (is_array($arg))
    
        // TODO...
    }

    public function fetch(array $opts = [])
    {
        return $this->queryDriver->fetchData($this->innerQuery, $opts);
    }
}
