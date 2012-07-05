<?php

namespace Rediska2;

class Manager
{
    /**
     * @var Manager
     */
    static protected $instance;

    /**
     * Redis instances
     *
     * @var \Redis[]
     */
    protected $instances = array();

    /**
     * Get instance
     *
     * @static
     * @return Manager
     */
    static public function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Set Redis instance
     *
     * @param $name
     * @param \Redis $redis
     * @return Manager
     */
    public function set($name, \Redis $redis)
    {
        $this->instances[$name] = $redis;

        return $this;
    }

    /**
     * Get Redis instance
     *
     * @param $name
     * @return \Redis
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException("Instance '$name' is not present");
        }

        return $this->instances[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->instances[$name]);
    }
}