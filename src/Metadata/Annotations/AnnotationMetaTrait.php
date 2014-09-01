<?php
namespace Agnostic\Metadata\Annotations;

use Doctrine\Common\Inflector\Inflector;

trait AnnotationMetaTrait
{
    public function getTag()
    {
        return substr(get_class($this), strlen(__NAMESPACE__) + 1);
    }
}
