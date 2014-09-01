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
     * @return Doctrine\DBAL\Query\QueryBuilder
     */
    public function createNativeQuery($tableName = null)
    {
        $queryBuilder = $this->conn->createQueryBuilder();

        if ($tableName) {
            $rootAlias = substr($tableName, 0, 1);
            $queryBuilder
                ->select(sprintf('%s.*', $rootAlias))
                ->from($tableName, $rootAlias)
                ;
        }

        return $queryBuilder;
    }

    public function addWhereIn($queryBuilder, $field, array $values)
    {
        $queryBuilder->where($queryBuilder->expr()->in($field, $values));

        return $queryBuilder;
    }

    public function fetchData($queryBuilder, array $opts = [])
    {
        $stmt = $queryBuilder->execute();
        $data = $stmt->fetchAll();
        return $data;
    }
}