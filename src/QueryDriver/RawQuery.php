<?php
namespace Agnostic\QueryDriver;

class RawQuery
{
    protected $name;

    protected $raw_query = '';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function add($fragment)
    {
        $this->raw_query .= $fragment;
    }

    public function get()
    {
        return $this->raw_query;
    }

    public function getName()
    {
        return $this->name;
    }
}
