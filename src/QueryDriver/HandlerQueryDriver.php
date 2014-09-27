<?php
namespace Agnostic\QueryDriver;

use Agnostic\QueryDriver\QueryDriverInterface;

class HandlerQueryDriver implements QueryDriverInterface
{
    protected $conn;

    public function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function createQuery($table_name = null)
    {
        $raw_query = new RawQuery;

        if ($table_name) {
            $raw_query->add(sprintf('HANDLER %s ', $table_name));
        }

        return $raw_query;
    }

    public function addWhereIn($raw_query, $field, array $values)
    {
        $raw_query->add(sprintf('READ %s (%s) ', $field, join(', ', $values)));
        return $raw_query;
    }

    public function fetchData($raw_query, array $opts = [])
    {
        // $this->conn->query(sprintf('HANDLER %s OPEN', $raw_query->getName()));

        $stmt = $this->conn->query($raw_query->get());
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // $this->conn->query(sprintf('HANDLER %s CLOSE', $raw_query->getName()));

        return $result;
    }

    public function toSql($raw_sql)
    {
        return $raw_sql->get();
    }
}
