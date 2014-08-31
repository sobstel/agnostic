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
 *   @Attribute("targetId", required=false, type="string"),
 *   @Attribute("throughEntity", required=true, type="string"),
 *   @Attribute("throughId", required=false, type="string"),
 *   @Attribute("throughTargetId", required=false, type="string"),
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

    public $throughId;

    public $throughTargetId;
}
