<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\Entity;

/**
 * @Entity(id="season_id", indexes={"competition_id"})
 * @BelongsTo(name="competition", targetEntity="Competition")
 * @HasMany(name="rounds", targetEntity="Round")
 */
class Season extends Entity
{
}
