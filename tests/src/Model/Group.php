<?php
namespace Agnostic\Tests\Model;

use Agnostic\Entity\Entity;

/*
 * @Entity(id="group_id", indexes={"round_id"})
 * @BelongsTo(name="round", targetEntity="Round")
 */
class Group extends Entity
{
}
