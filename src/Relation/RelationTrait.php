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

    public function getThrough()
    {
        return $this->through;
    }

    public function getThroughNativeField()
    {
        return $this->through_native_field;
    }

    public function getThroughForeignField()
    {
        return $this->through_foreign_field;
    }
}
