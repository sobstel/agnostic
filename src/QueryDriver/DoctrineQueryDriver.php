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
     * @return Doctrine\ORM\QueryBuilder
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
