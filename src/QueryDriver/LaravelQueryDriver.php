<?php
namespace Agnostic\QueryDriver;

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'sqlite',
    'host'      => 'localhost',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

    protected $capsule;

    public function __construct(Capsule $capsule)
    {
        $this->capsule = $capsule;
    }

    /**
     * @return Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQuery($tableName = null)
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