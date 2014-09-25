<?php
namespace Agnostic\QueryDriver;

use Agnostic\QueryDriver\QueryDriverInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

class IlluminateQueryDriver implements QueryDriverInterface
{
    /**
     * {@inheritdoc}
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function createQuery($table_name = null)
    {
        if (!$table_name) {
            throw new \Exception(sprintf('tableName is required for %s', get_class($this)));
        }
        return Capsule::table($table_name);
    }

    public function addWhereIn($query_builder, $field, array $values)
    {
        return $query_builder->whereIn($field, $values);
    }

    public function fetchData($query_builder, array $opts = [])
    {
        return $query_builder->get();
    }

    public function toSql($query_builder)
    {
        return sprintf('%s [%s]', $query_builder->toSql(), join(', ', $query_builder->getBindings()));
    }
}
