<?php
namespace Agnostic\QueryDriver;

use Agnostic\QueryDriver\QueryDriverInterface;
use Doctrine\DBAL\Connection;

class DoctrineQueryDriver implements QueryDriverInterface
{
    protected $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * {@inheritdoc}
     *
     * @return Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQuery($table_name = null)
    {
        $query_builder = $this->conn->createQueryBuilder();

        if ($table_name) {
            $root_alias = substr($table_name, 0, 1);
            $query_builder
                ->select(sprintf('%s.*', $root_alias))
                ->from($table_name, $root_alias)
                ;
        }

        return $query_builder;
    }

    public function addWhereIn($query_builder, $field, array $values)
    {
        $query_builder->andWhere($query_builder->expr()->in($field, $values));

        return $query_builder;
    }

    public function fetchData($query_builder, array $opts = [])
    {
        $stmt = $query_builder->execute();
        $data = $stmt->fetchAll();
        return $data;
    }

    public function toSql($query_builder)
    {
        return (string)$query_builder;
    }
}
