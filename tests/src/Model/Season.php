<?php
namespace Agnostic\Tests\Model;

use Agnostic\Entity\Entity;

/*
 * @Entity(id="season_id", indexes={"competition_id"})
 * @BelongsTo(name="competition", targetEntity="Competition")
 * @HasMany(name="rounds", targetEntity="Round")
 */
class Season extends Entity
{
}
