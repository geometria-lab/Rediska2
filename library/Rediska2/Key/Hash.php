<?php

namespace Rediska2\Key;

class Hash extends AbstractKey implements \Countable, \IteratorAggregate
{
    public function set($field, $value, $overwrite = true)
    {
        if ($overwrite) {
            return $this->getRedis()->hSet($this->getName(), $field, $value);
        } else {
            return $this->getRedis()->hSetNx($this->getName(), $field, $value);
        }
    }

    public function setMultiple($fieldsAndValue)
    {
        return $this->getRedis()->hMSet($this->getName(), $fieldsAndValue);
    }

    public function has($field)
    {
        return $this->getRedis()->hExists($this->getName(), $field);
    }

    public function get($field)
    {
        return $this->getRedis()->hGet($this->getName(), $field);
    }

    public function getMultiple($fields)
    {
        return $this->getRedis()->hMGet($this->getName(), $fields);
    }

    public function getAll()
    {
        return $this->getRedis()->hGetAll($this->getName());
    }

    public function getFields()
    {
        return $this->getRedis()->hKeys($this->getName());
    }

    public function getValues()
    {
        return $this->getRedis()->hVals($this->getName());
    }

    public function getLength()
    {
        return $this->getRedis()->hLen($this->getName());
    }

    public function remove($field)
    {
        return $this->getRedis()->hDel($this->getName(), $field);
    }

    public function removeMultiple($fields)
    {
        $arguments = array_merge(array($this->getName()), $fields);
        return call_user_func_array(array($this->getRedis(), 'hDel'), $arguments);
    }

    public function increment($field, $amount = 1)
    {
        if (is_float($amount)) {
            return $this->getRedis()->hIncrByFloat($this->getName(), $field, $amount);
        } else {
            return $this->getRedis()->hIncrBy($this->getName(), $field, $amount);
        }
    }

    /**
     * Implements field object access
     */

    public function __set($field, $value)
    {
        return $this->set($field, $value);
    }

    public function __get($field)
    {
        return $this->get($field);
    }

    public function __isset($field)
    {
        return $this->has($field);
    }

    public function __unset($field)
    {
        return $this->remove($field);
    }

    /**
     * Implements Countable
     */

    public function count()
    {
        return $this->getLength();
    }

    /**
     * Implements IteratorAggregate
     */

    public function getIterator()
    {
        return new \ArrayIterator($this->getAll());
    }
}
