<?php
namespace Agnostic\QueryDriver;

use Agnostic\QueryDriver\QueryDriverInterface;
use Agnostic\QueryDriver\DebugQueryDriver;

class Manager
{
    protected $query_drivers = [];

    protected $debug = false;

    public function set(QueryDriverInterface $query_driver, $name = 'default')
    {
        if ($this->debug) {
            $query_driver = new DebugQueryDriver($query_driver);
        }

        $this->query_drivers[$name] = $query_driver;
    }

    public function get($name = 'default')
    {
        if (!$this->exists($name)) {
            throw new \Agnostic\Exception\Exception(sprintf('Query driver "%s" not defined, add it using Agnostic\Manager::getQueryDriverManager()->set() method', $name));
        }

        return $this->query_drivers[$name];
    }

    public function exists($name = 'default')
    {
        return isset($this->query_drivers[$name]);
    }

    public function alias($name, $target)
    {
        $this->query_drivers[$name] = $this->get($target);
    }

    public function debug($debug = true)
    {
        $this->debug = $debug;
    }
}
