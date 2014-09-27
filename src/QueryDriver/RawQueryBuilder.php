<?php
namespace Agnostic\QueryDriver;

/**
 * Raw plain string query.
 */
class RawQueryBuilder
{
    /*** @var string */
    protected $raw_query = '';

    /**
     * @param string
     */
    public function set($raw_query)
    {
        $this->raw_query = $raw_query;
        return $this;
    }

    /**
     * @param string
     */
    public function append($fragment)
    {
        $this->raw_query .= $fragment;
        return $this;
    }

    /**
     * @return string
     */
    public function build()
    {
        return $this->raw_query;
    }

    public function __toString()
    {
        return $this->build();
    }
}
