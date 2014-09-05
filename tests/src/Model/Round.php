<?php
namespace Agnostic\Tests\Model;

use Agnostic\Entity\Entity;

/**
 * @Entity(id="round_id", indexes={"season_id"})
 * @BelongsTo(name="season", targetEntity="Season")
 * @HasMany(name="matches", targetEntity="Match")
 */
class Round extends Entity
{
}
