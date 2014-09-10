<?php
namespace Agnostic\Query;

use Agnostic\Metadata\Metadata;
use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\Query\Factory as QueryFactory;
use Agnostic\Marshal\Manager as MarshalManager;

use IteratorAggregate;
use ArrayIterator;

class Query implements IteratorAggregate
{
    protected $relatedNames = [];

    /**
     * @param mixed
     * @return Query
     */
    public function with($relatedName)
    {
        $this->relatedNames[] = $relatedName;

        // if (is_array($spec)) {
        //     $names = $spec;
        // } else {
        //     $names = [$spec];
        // }

        // foreach ($names as $name) {
            // $this->related = $name;
        // }

        // foreach ($names as $k => $v) {
        //     $isNestedSpec = (!is_int($k));

        //     $name = $isNestedSpec ? $k : $v;

        //     $relationMetadata = $this->metadata['relations'][$name];
        //     $targetQuery = $this->queryFactory->create($relationMetadata['targetEntity']);
        //     $this->queryStack->add($name, $targetQuery);

        //     if ($isNestedSpec) {
        //         $targetQuery->with($v);
        //     }
        // }

        return $this;
    }
}
