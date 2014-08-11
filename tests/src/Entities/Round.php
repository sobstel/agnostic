<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\GenericEntity;

/**
 * @Entity(id="round_id", indexes={"season_id"})
 * @BelongsTo(name="season", targetEntity="Season", id="season_id", targetId="season_id")
 * @HasMany(name="matches", targetEntity="Match")
 */
class Round extends GenericEntity
{
}
