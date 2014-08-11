<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\GenericEntity;

/**
 * @Entity(id="group_id", indexes={"round_id"})
 * @BelongsTo(name="round", targetEntity="Round")
 */
class Group extends GenericEntity
{
}
