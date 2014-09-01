<?php
namespace Agnostic\Metadata\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("tableName", required=false, type="string"),
 *   @Attribute("id", required=false, type="string"),
 *   @Attribute("indexes", required=false, type= "array"),
 *   @Attribute("queryClassName", required=false, type="string")
 * })
 */
class Entity extends Annotation
{
    use AnnotationMetaTrait;

    public $tableName;

    // identity field
    public $id;

    // index fields
    public $indexes;

    public $queryClassName;
}
