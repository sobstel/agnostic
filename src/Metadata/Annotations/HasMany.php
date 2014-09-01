<?php
namespace Agnostic\Metadata\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("name", required=true, type="string"),
 *   @Attribute("targetEntity", required=true, type="string"),
 *   @Attribute("id", required=false, type="string"),
 *   @Attribute("targetId", required=false, type="string"),
 * })
 */
class HasMany extends Annotation
{
    use AnnotationMetaTrait;

    public $name;

    public $targetEntity;

    public $id;

    public $targetId;
}
