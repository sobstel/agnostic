<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\GenericEntity;

/**
 * @Entity(id="match_id", indexes={"round_id","team_A_id","team_B_id"})
 * @BelongsTo(name="round", targetEntity="Round", id="round_id", targetId="round_id")
 */
class Match extends GenericEntity
{
}
