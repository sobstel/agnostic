<?php
namespace Agnostic\Tests\Model;

use Agnostic\Type\Type;

class MatchType extends Type
{
    public function withGoals(Query $query)
    {
        $query->goals();

        return $this;
    }

    public function withEventsCount(Query $query, Relation $relation)
    {
        // $parentQuery = $query->getParentQuery()
        // $parentQuery

        // createRelatedQuery()

 //       ? $query->getRelation()
        $relation['foreign_field'];

        $query
            ->select('m.match_id, count(*) num')
            ->groupBy('m.match_id'); // parent[id]

        return $this;
    }
}
