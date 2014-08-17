<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\Entity;

/**
 * @Entity(id="match_id", indexes={"round_id","team_A_id","team_B_id"})
 * @BelongsTo(name="round", targetEntity="Round")
 * @BelongsTo(name="group", targetEntity="Group")
 * @BelongsTo(name="venue", targetEntity="Venue")
 * @BelongsTo(name="team_A", targetEntity="Team", id="team_A_id", targetId="team_id")
 * @BelongsTo(name="team_B", targetEntity="Team", id="team_B_id", targetId="team_id")
 * @HasMany(name="events", targetEntity="Event")
 */
class Match extends Entity
{
	// events->count
}
