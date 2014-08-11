<?php
namespace Agnostic\Tests\Entities;

use Agnostic\Entity\GenericEntity;

/**
 * @Entity(id="competition_id")
 * @HasMany(name="seasons", targetEntity="Season")
 */
class Competition extends GenericEntity
{
}
