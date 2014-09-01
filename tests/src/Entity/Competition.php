<?php
namespace Agnostic\Tests\Entity;

use Agnostic\Entity\Entity;

/**
 * @Entity(id="competition_id")
 * @HasMany(name="seasons", targetEntity="Season")
 */
class Competition extends Entity
{
}
