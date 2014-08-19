<?php
namespace Agnostic\Query\Doctrine;

use Agnostic\Query\QueryDriverInterface;
use Doctrine\DBAL\Connection;

class DbalQueryDriver implements QueryDriverInterface
{
    protected $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @return Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQuery($typeName)
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($typeName, substr($typeName, 0, 1))
            ;
        
        return $queryBuilder;
    }

    public function createFinderQuery($typeName, $field, array $values)
    {
        $queryBuilder = $this->createQuery($typeName);
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
