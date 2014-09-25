<?php
namespace Agnostic\QueryDriver;

interface QueryDriverInterface
{
    /**
     * @param string
     * @return object Query object
     */
    public function createQuery($tableName = null);

    /**
     * @param object
     * @param string
     * @param array
     * @return object Query object
     */
    public function addWhereIn($nativeQuery, $field, array $values);

    /**
     * Fetches data from query object
     *
     * @param object Query object
     */
    public function fetchData($nativeQuery, array $opts = []);

    /**
     * Converts to raw SQL string.
     *
     * @param object Query object
     */
    public function toSql($nativeQuery);
}
