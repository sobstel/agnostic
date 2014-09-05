<?php
namespace Agnostic\Metadata\Annotation;

use Agnostic\Metadata\Annotation\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 * @Attributes({
 *   @Attribute("entity", required=false, type="string"),
 *   @Attribute("table", required=false, type="string"),
 *   @Attribute("identityField", required=false, type="string"),
 * })
 */
class Query extends Annotation
{
    public $entity;

    public $table;

    public $identityField;
}
