<?php

namespace Rediska2\Key;

class String extends AbstractKey
{
    public function setValue($value)
    {
        return $this->getRedis()->set($this->getName(), $value);
    }

    public function getValue()
    {
        return $this->getRedis()->get($this->getName());
    }

    public function increment($amount = 1)
    {
        if (is_float($amount)) {
            return $this->getRedis()->incrByFloat($this->getName(), $amount);
        } else {
            return $this->getRedis()->incrBy($this->getName(), $amount);
        }
    }

    public function decrement($amount = 1)
    {
        return $this->getRedis()->decrBy($this->getName(), $amount);
    }
}