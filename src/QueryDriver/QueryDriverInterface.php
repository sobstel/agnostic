<?php
namespace Agnostic\QueryDriver;

interface QueryDriverInterface
{ 
    /**
     * @param string
     * @return object
     */
    public function createQuery($typeName);

    /**
     * @param string
     * @param string
     * @param array
     * @return object
     */
    public function createFinderQuery($typeName, $field, array $values);

    /**
     * Fetches data from query object
     *
     * @param object
     */
    public function fetchData($query, array $opts = []);
}
