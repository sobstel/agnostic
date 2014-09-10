<?php
namespace Agnostic\Type\Annotation;

use Agnostic\Type\Annotation\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("name", required=true, type="string"),
 *   @Attribute("query", required=true, type="string"),
 *   @Attribute("nativeField", required=false, type="string"),
 *   @Attribute("foreignField", required=false, type="string"),
 * })
 */
class BelongsTo extends Annotation
{
    public $name;

    public $query;

    public $nativeField;

    public $foreignField;
}
