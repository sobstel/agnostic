<?php
namespace Agnostic\Query;

use Agnostic\Query\Query;
use Agnostic\Collection\Collection;

class QueryStack
{
    /*** @var Query */
    protected $rootQuery;

    /*** @var array */
    protected $queries = [];

    public function __construct($rootQuery)
    {
        $this->rootQuery = $rootQuery;
    }

    public function add($name, Query $query)
    {
        $this->queries[$name] = $query;
    }

    public function fetch(Collection $collection)
    {
        foreach ($this->queries as $name => $query) {
            $relationMetadata = $this->rootQuery->getEntityMetadata()['relations'][$name];
            $ids = array_unique($collection->getFieldValues($relationMetadata['id']));

            switch ($relationMetadata['relationship']) {
                case 'BelongsTo':
                case 'HasOne':
                case 'HasMany':
                    $query->findBy($relationMetadata['targetId'], $ids)->fetch();
                break;

                case 'HasManyThrough':
                    // @todo
                    throw new \Agnostic\Exception('HasManyThrough not supported yet');
                break;
            }
        }
    }
}
