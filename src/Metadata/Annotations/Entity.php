<?php
namespace Agnostic\Metadata\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("typeName", required=false, type="string"),
 *   @Attribute("id", required=false, type="string"),
 *   @Attribute("indexes", required=false, type= "array"),
 *   @Attribute("queryClassName", required=false, type="string")
 * })
 */
class Entity extends Annotation
{
    use AnnotationMetaTrait;

    public $typeName;

    // identity field
    public $id;

    // index fields
    public $indexes;

    public $queryClassName;
}
