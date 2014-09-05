<?php
namespace Agnostic\Metadata\Annotation;

use Doctrine\Common\Annotations\Annotation as BaseAnnotation;

class Annotation extends BaseAnnotation
{
    public function getTag()
    {
        return substr(get_class($this), strlen(__NAMESPACE__) + 1);
    }
}
