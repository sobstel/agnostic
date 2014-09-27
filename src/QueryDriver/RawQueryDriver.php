<?php
namespace Agnostic\QueryDriver;

use Agnostic\QueryDriver\QueryDriverInterface;
use PDO;
use Agnostic\QueryDriver\RawQueryBuilder;

class RawQueryDriver implements QueryDriverInterface
{
    protected $conn;

    /**
     * @param PDO
     */
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @inheritdoc
     */
    public function createQuery($table_name = null)
    {
        $query_builder = new RawQueryBuilder;

        if ($table_name) {
            $query_builder->set(sprintf('SELECT * FROM %s', $table_name));
        }

        return $query_builder;
    }

    /**
     * @param RawQueryBuilder
     * @param string
     * @param array
     * @return RawQueryBuilder
     */
    public function addWhereIn($query_builder, $field, array $values)
    {
        if (empty($values)) {
            $query_builder->append(' WHERE 1=0');
        } else {
            $query_builder->append(sprintf(' WHERE %s IN (%s)', $field, join(', ', $values)));
        }

        return $query_builder;
    }

    /**
     * @param RawQueryBuilder
     * @param array
     * @return mixed
     */
    public function fetchData($query_builder, array $opts = [])
    {
        $fetch_style = isset($opts['fetch_style']) ? $opts['fetch_style'] : PDO::FETCH_ASSOC;

        $stmt = $this->conn->query($query_builder->build());
        $data = $stmt->fetchAll($fetch_style);

        return $data;
    }

    /**
     * @param RawQueryBuilder
     * @return string
     */
    public function toSql($query_builder)
    {
        return $query_builder->build();
    }
}
