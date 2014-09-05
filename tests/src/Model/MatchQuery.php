<?php
namespace Agnostic\Tests\Model;

use Agnostic\Query\Query;

/**
 * @Queryy(entity="Match", table="matches", identityField="match_id")
 * @BelongsTo(name="round", query="Round")
 * @BelongsTo(name="group", query="Group")
 * @BelongsTo(name="venue", query="Venue")
 * @BelongsTo(name="teamA", query="Team", nativeField="team_A_id")
 * @BelongsTo(name="teamB", query="Team", nativeField="team_B_id")
 * @HasMany(name="events", query="Event")
 */
//  * automatically read from relations native_fields: index_fields={"round_id","team_A_id","team_B_id"})
class MatchQuery extends Query
{
    /*** @Related(query="events") */
    public function withGoals(Query $query)
    {
        $query->goals();

        return $this;
    }

    /*** @HasOne(query="events", targetEntity="Entity") */
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
