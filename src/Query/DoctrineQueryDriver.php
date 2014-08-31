<?php
namespace Agnostic\Query;

use Agnostic\Query\QueryDriverInterface;
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
    public function createBaseQuery($typeName)
    {
        $rootAlias = substr($typeName, 0, 1);

        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder
            ->select(sprintf('%s.*', $rootAlias))
            ->from($typeName, $rootAlias)
            ;
        
        return $queryBuilder;
    }

    public function createFinderQuery($typeName, $field, array $values)
    {
        $queryBuilder = $this->createBaseQuery($typeName);
        $queryBuilder
            ->where($queryBuilder->expr()->in($field, $values))
            ;

        return $queryBuilder;
    }

    public function fetchData($query, array $opts = [])
    {
        $stmt = $query->execute();
        $data = $stmt->fetchAll();
        return $data;
    }
}
