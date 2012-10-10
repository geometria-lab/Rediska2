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
            $result = $this->getRedis()->rename($this->getName(), $newName);
        } else {
            $result = $this->getRedis()->renameNx($this->getName(), $newName);
        }

        $this->setName($newName);

        return $result;
    }

    public function moveToDb($dbIndex)
    {
        return $this->getRedis()->move($this->getName(), $dbIndex);
    }

    public function expire($seconds, $isMilliseconds = false)
    {
        if ($isMilliseconds) {
            throw new \RuntimeException('Not implemented yet');
        } else {
            return $this->getRedis()->expire($this->getName(), $seconds);
        }
    }

    public function expireAt($timestamp, $isMilliseconds = false)
    {
        if ($isMilliseconds) {
            throw new \RuntimeException('Not implemented yet');
        } else {
            return $this->getRedis()->expireAt($this->getName(), $timestamp);
        }
    }

    public function getLifetime($milliseconds = false)
    {
        if ($milliseconds) {
            return $this->getRedis()->pttl($this->getName());
        } else {
            return $this->getRedis()->ttl($this->getName());
        }
    }

    public function persist()
    {
        return $this->getRedis()->persist($this->getName());
    }

    public function __toString()
    {
        return $this->getName();
    }
}
