<?php
namespace Agnostic\Entity\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("name", required=true, type="string"),
 *   @Attribute("targetEntity", required=true, type="string"),
 *   @Attribute("id", required=false, type="string"),
 *   @Attribute("throughEntity", required=false, type="string"),
 * })
 *
 * Either throughEntity or throughType must be provided.
 */
class HasManyThrough extends Annotation
{
    use AnnotationMetaTrait;

    public $name;

    public $targetEntity;

    public $id;

    public $targetId;

    public $throughEntity;

    public $throughType;

    public $throughId;

    public $throughTargetId;
}
