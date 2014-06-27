<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\GenericEntity;

/**
 * @Entity(id="season_id", indexes={"competition_id"})
 * @BelongsTo(name="competition", targetEntity="Competition", id="competition_id", targetId="competition_id")
 */
class Season extends GenericEntity
{
}
