<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\GenericEntity;

/**
 * @Entity(id="event_id", indexes={"match_id","player_id","team_id"})
 * @BelongsTo(name="match", targetEntity="Match")
 */
class Event extends GenericEntity
{
}
