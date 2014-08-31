<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\Entity;

/**
 * @Entity(id="match_id", indexes={"round_id","team_A_id","team_B_id"})
 * @BelongsTo(name="round", targetEntity="Round")
 * @BelongsTo(name="group", targetEntity="Group")
 * @BelongsTo(name="venue", targetEntity="Venue")
 * @BelongsTo(name="teamA", targetEntity="Team", id="team_A_id")
 * @BelongsTo(name="teamB", targetEntity="Team", id="team_B_id")
 * @HasMany(name="events", targetEntity="Event")
 *
 * no, jut use methods on top of existing relations!
 * HasMany(name="goals", targetEntity="Event", scope={"goals"})
 */
class Match extends Entity
{
}
