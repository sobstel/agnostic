<?php
namespace Agnostic\Tests\Entity;

use Agnostic\Entity\Entity;

/**
 * @Entity(id="group_id", indexes={"round_id"})
 * @BelongsTo(name="round", targetEntity="Round")
 */
class Group extends Entity
{
}
