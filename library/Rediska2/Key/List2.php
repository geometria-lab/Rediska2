<?php

namespace Rediska2\Key;

class List2 extends AbstractSet implements \ArrayAccess
{
    public function shift()
    {
        return $this->getRedis()->lPop($this->getName());
    }

    public function shiftBlocking()
    {
        return $this->getRedis()->blPop($this->getName());
    }

    public function popBlocking()
    {
        return $this->getRedis()->brPop($this->getName());
    }

    public function popAndPushBlocking($destinationKey, $timeout = 0)
    {
        return $this->getRedis()->brpoplpush($this->getName(), (string)$destinationKey, $timeout);
    }

    public function get($index)
    {
        return $this->getRedis()->lIndex($this->getName(), $index);
    }

    public function insertBefore($pivot, $value)
    {
        return $this->getRedis()->lInsert($this->getName(), Redis::BEFORE, $pivot, $value);
    }

    public function insertAfter($pivot, $value)
    {
        return $this->getRedis()->lInsert($this->getName(), Redis::AFTER, $pivot, $value);
    }

    public function prepend($value)
    {
        return $this->getRedis()->lPush($this->getName(), $value);
    }

    public function prependMultiple(array $values)
    {
        $arguments = array_merge(array($this->getName()), $values);
        return call_user_func_array(array($this->getRedis(), 'lPush'), $arguments);
    }

    public function prependIfExists($value)
    {
        return $this->getRedis()->lPushx($this->getName(), $value);
    }

    public function getRange($start, $stop)
    {
        return $this->getRedis()->lRange($this->getName(), $start, $stop);
    }

    public function remove($value, $count = 0)
    {
        return $this->getRedis()->lRem($this->getName(), $value, $count);
    }

    public function set($index, $value)
    {
        return $this->getRedis()->lSet($this->getName(), $index, $value);
    }

    public function trim($start, $end)
    {
        return $this->getRedis()->lTrim($this->getName(), $start, $end);
    }

    public function pop()
    {
        return $this->getRedis()->rPop($this->getName());
    }

    public function popAndPush($destinationKey)
    {
        return $this->getRedis()->rpoplpush($this->getName(), (string)$destinationKey);
    }

    public function append($value)
    {
        return $this->getRedis()->rPush($this->getName(), $value);
    }

    public function appendMultiple(array $values)
    {
        $arguments = array_merge(array($this->getName()), $values);
        return call_user_func_array(array($this->getRedis(), 'rPush'), $arguments);
    }

    public function appendIfExists($value)
    {
        return $this->getRedis()->rPushx($this->getName(), $value);
    }

    /**
     * Implements items array access
     */

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->append($value);
        } else {
            $this->set($offset, $value);
        }

        return $value;
    }

    public function offsetExists($offset)
    {
        return (boolean)$this->get($offset);
    }

    public function offsetUnset($offset)
    {
        throw new Rediska_Key_Exception("Redis not support delete by index. User 'remove' method for delete by value");
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}