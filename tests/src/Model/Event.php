<?php
namespace Agnostic\Tests\Model;

use Agnostic\Entity\Entity;

/*
 * @Entity(id="event_id", indexes={"match_id","player_id","team_id"})
 * @BelongsTo(name="match", targetEntity="Match")
 */
class Event extends Entity
{
}
