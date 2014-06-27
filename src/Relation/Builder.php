<?php
namespace Agnostic\Relation;

use Aura\Marshal\Relation\Builder as BaseRelationBuilder;
use Agnostic\Manager;

class Builder extends BaseRelationBuilder
{
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }
}
