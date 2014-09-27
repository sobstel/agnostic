<?php
namespace Agnostic\QueryDriver;

interface QueryDriverInterface
{
    /**
     * @param string
     * @return object
     */
    public function createQuery($table_name = null);

    /**
     * @param object
     * @param string
     * @param array
     * @return object
     */
    public function addWhereIn($query_builder, $field, array $values);

    /**
     * Fetches data from query object
     *
     * @param object Query object
     */
    public function fetchData($query_builder, array $opts = []);

    /**
     * Converts to raw SQL string.
     *
     * @param object Query object
     */
    public function toSql($query_builder);
}
