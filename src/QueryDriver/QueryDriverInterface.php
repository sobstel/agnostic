<?php
namespace Agnostic\QueryDriver;

interface QueryDriverInterface
{ 
    /**
     * @param string
     * @return object
     */
    public function baseQuery($typeName);

    /**
     * @param string
     * @param string
     * @param array
     * @return object
     */
    public function finderQuery($typeName, $field, array $values);

    /**
     * Fetches data from query object
     *
     * @param object
     */
    public function fetchData($query);
}
