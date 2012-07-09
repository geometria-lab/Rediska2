<?php

namespace Rediska2\Key;

class String extends AbstractKey implements \Countable, \ArrayAccess
{
    public function setValue($value, $overwrite = true)
    {
        if ($overwrite) {
            return $this->getRedis()->set($this->getName(), $value);
        } else {
            return $this->getRedis()->setnx($this->getName(), $value);
        }
    }

    public function getValue()
    {
        $value = $this->getRedis()->get($this->getName());

        if ($value === false) {
            $value = null;
        }

        return $value;
    }

    public function getAndSetValue($value)
    {
        return $this->getRedis()->getSet($this->getName(), $value);
    }

    public function setAndExpire($value, $seconds, $isMilliseconds = false)
    {
        if ($isMilliseconds) {
            throw new \RuntimeException('Not implemented yet');
        } else {
            return $this->getRedis()->setex($this->getName(), $seconds, $value);
        }
    }

    public function append($value)
    {
        return $this->getRedis()->append($this->getName(), $value);
    }

    public function setBit($offset, $value)
    {
        return $this->getRedis()->setBit($this->getName(), $offset, $value);
    }

    public function getBit($offset)
    {
        return $this->getRedis()->getBit($this->getName(), $offset);
    }

    public function setRange($offset, $value)
    {
        return $this->getRedis()->setRange($this->getName(), $offset, $value);
    }

    public function getRange($start, $end)
    {
        return $this->getRedis()->getRange($this->getName(), $start, $end);
    }

    public function getLength()
    {
        return $this->getRedis()->strlen($this->getName());
    }

    public function increment($amount = 1)
    {
        if (is_float($amount)) {
            throw new \RuntimeException('Not implemented yet!');
        } else {
            return $this->getRedis()->incrBy($this->getName(), $amount);
        }
    }

    public function decrement($amount = 1)
    {
        return $this->getRedis()->decrBy($this->getName(), $amount);
    }

    public function __toString()
    {
        return (string)$this->getValue();
    }

    /**
     * Implements Countable
     */

    public function count()
    {
        return $this->getLength();
    }

    /**
     * Implements ArrayAccess
     */

    public function offsetExists($offset)
    {
        return (boolean)$this->getBit($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getBit($offset);
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            throw new \InvalidArgumentException("You must specify offset");
        }

        return $this->setBit($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->setBit($offset, 0);
    }
}