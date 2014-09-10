<?php
namespace Agnostic\Type\Annotation;

use Agnostic\Type\Annotation\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("entity", required=false, type="string"),
 *   @Attribute("table", required=false, type="string"),
 *   @Attribute("identityField", required=false, type="string"),
 * })
 */
class Type extends Annotation
{
    public $entity;

    public $table;

    public $identityField;
}
