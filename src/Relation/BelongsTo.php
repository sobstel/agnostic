<?php
namespace Agnostic\Relation;

use Aura\Marshal\Relation\BelongsTo as BaseBelongsTo;
use Aura\Marshal\Type\GenericType;

class BelongsTo extends BaseBelongsTo
{
    use RelationTrait;
}
