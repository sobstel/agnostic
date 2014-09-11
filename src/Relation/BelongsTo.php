<?php
namespace Agnostic\Relation;

use Aura\Marshal\Relation\BelongsTo as BaseBelongsTo;

class BelongsTo extends BaseBelongsTo
{
    // @todo: set default
    public function __construct(
        $native_field,
        GenericType $foreign,
        $foreign_type,
        $foreign_field,
        GenericType $through = null,
        $through_type = null,
        $through_native_field = null,
        $through_foreign_field = null
    ) {
        $this->native_field          = $native_field;
        $this->foreign               = $foreign;
        $this->foreign_type          = $foreign_type;
        $this->foreign_field         = $foreign_field;
        $this->through               = $through;
        $this->through_type          = $through_type;
        $this->through_native_field  = $through_native_field;
        $this->through_foreign_field = $through_foreign_field;
    }
}
