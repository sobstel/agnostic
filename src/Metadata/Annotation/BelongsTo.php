<?php
namespace Agnostic\Metadata\Annotation;

use Agnostic\Metadata\Annotation\Annotation;

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
