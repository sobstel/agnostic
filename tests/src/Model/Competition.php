<?php
namespace Agnostic\Tests\Model;

use Agnostic\Entity\Entity;

/**
 * @Entity(id="competition_id")
 * @HasMany(name="seasons", targetEntity="Season")
 */
class Competition extends Entity
{
}
