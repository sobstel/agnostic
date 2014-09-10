<?php
namespace Agnostic\Relation;

use Aura\Marshal\Relation\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    protected $relationship_class = [
        'belongs_to' => 'Agnostic\Relation\BelongsTo',
        'has_one' => 'Agnostic\Relation\HasOne',
        'has_many' => 'Agnostic\Relation\HasMany',
        'has_many_through' => 'Agnostic\Relation\HasManyThrough',
    ];
}
