<?php
namespace Agnostic\Entity\Annotations;

use Doctrine\Common\Annotations\Annotation;
/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("type", required = false, type = "string"),
 *   @Attribute("id", required = false, type = "string"),
 *   @Attribute("indexes", required = false, type = "array"),
 * })
 */
class Entity extends Annotation
{
    use AnnotationMetaTrait;

    // identity field
    public $id;

    // index fields
    public $indexes;
}
