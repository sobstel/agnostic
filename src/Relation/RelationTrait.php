<?php
namespace Agnostic\Relation;

use Doctrine\Common\Inflector\Inflector;

trait RelationTrait
{
    public function getRelationship()
    {
        return Inflector::tableize(substr(get_class($this), strlen(__NAMESPACE__) + 1));
    }

    public function getNativeField()
    {
        return $this->native_field;
    }

    public function getForeign()
    {
        return $this->foreign;
    }

    public function getForeignField()
    {
        return $this->foreign_field;
    }
}
