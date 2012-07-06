<?php

namespace Rediska2\Key;

use Rediska2\Manager;

abstract class AbstractKey
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $redis = 'default';

    public function __construct($name, array $options = array())
    {
        $this->setName($name);
        $this->setOptions($options);
    }

    public function setOptions(array $options)
    {
        foreach($options as $option => $value) {
            $method = "set$option";
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                throw new \InvalidArgumentException("Unknown property option '$option'");
            }
        }
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Redis
     */
    public function getRedis()
    {
        return Manager::getInstance()->get($this->redis);
    }

    public function setRedis($redis)
    {
        if (!Manager::getInstance()->has($redis)) {
            throw new \InvalidArgumentException('Redis must be instance name');
        }

        $this->redis = $redis;

        return $this;
    }

    public function delete()
    {
        return $this->getRedis()->delete($this->getName());
    }

    public function isExists()
    {
        return $this->getRedis()->exists($this->getName());
    }

    public function getType()
    {
        return $this->getRedis()->type($this->getName());
    }

    public function rename($newName, $overwrite = true)
    {
        if ($overwrite) {
            return $this->getRedis()->rename($this->getName(), $newName);
        } else {
            return $this->getRedis()->renameNx($this->getName(), $newName);
        }
    }

    public function moveToDb($dbIndex)
    {
        return $this->getRedis()->move($this->getName(), $dbIndex);
    }
}
