<?php
namespace Agnostic\Query;

interface QueryDriverInterface
{ 
    /**
     * @param string
     * @return object Query object
     */
    public function createQuery($typeName);

    /**
     * @param string
     * @param string
     * @param array
     * @return object Query object
     */
    public function createFinderQuery($typeName, $field, array $values);

    /**
     * Fetches data from query object
     *
     * @param object Query object
     */
    public function fetchData($query, array $opts = []);
}
