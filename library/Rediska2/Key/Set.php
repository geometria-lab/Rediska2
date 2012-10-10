<?php

namespace Rediska2\Key;

class Set extends AbstractSet
{
    public function add($member)
    {
        return $this->getRedis()->sAdd($this->getName(), $member);
    }

    public function addMultiple(array $members)
    {
        $args = array_merge(array($this->getName()), $members);

        return call_user_func_array(array($this->getRedis(), 'sAdd'), $args);
    }

    public function has($element)
    {
        return $this->getRedis()->sIsMember($this->getName(), $element);
    }

    public function getAndRemoveRandom()
    {
        return $this->getRedis()->sPop($this->getName());
    }

    public function getRandom()
    {
        return $this->getRedis()->sRandMember($this->getName());
    }

    public function getAll()
    {
        return $this->getRedis()->sMembers($this->getName());
    }

    public function remove($element)
    {
        return $this->getRedis()->sRem($this->getName(), $element);
    }

    public function removeMultiple(array $elements)
    {
        $args = array_merge(array($this->getName()), $elements);

        return call_user_func_array(array($this->getRedis(), 'sRem'), $args);
    }

    public function move($element, $destinationKey)
    {
        return $this->getRedis()->sMove($this->getName(), (string)$destinationKey, $element);
    }

    public function getLength()
    {
        return $this->getRedis()->sCard($this->getName());
    }

    public function getUnion($key)
    {
        return $this->getRedis()->sUnion($this->getName(), (string)$key);
    }

    public function getUnionMultiple(array $keys)
    {
        return $this->compareMultiple('sUnion', $keys);
    }

    public function getDiff($key)
    {
        return $this->getRedis()->sDiff($this->getName(), (string)$key);
    }

    public function getDiffMultiple(array $keys)
    {
        return $this->compareMultiple('sDiff', $keys);
    }

    public function storeUnion($key, $destinationKey)
    {
        return $this->getRedis()->sUnionStore((string)$destinationKey, $this->getName(), (string)$key);
    }

    public function storeUnionMultiple(array $keys, $destinationKey)
    {
        return $this->compareMultiple('sUnionStore', $keys, $destinationKey);
    }

    public function storeDiff($key, $destinationKey)
    {
        return $this->getRedis()->sDiffStore((string)$destinationKey, $this->getName(), (string)$key);
    }

    public function storeDiffMultiple(array $keys, $destinationKey)
    {
        return $this->compareMultiple('sDiffStore', $keys, $destinationKey);
    }

    public function getIntersection($key)
    {
        return $this->getRedis()->sInter($this->getName(), (string)$key);
    }

    public function getIntersectionMultiple(array $keys)
    {
        return $this->compareMultiple('sInter', $keys);
    }

    public function storeIntersection($key, $destinationKey)
    {
        return $this->getRedis()->sDiffStore((string)$destinationKey, $this->getName(), (string)$key);
    }

    public function storeIntersectionMultiple(array $keys, $destinationKey)
    {
        return $this->compareMultiple('sInterStore', $keys, $destinationKey);
    }

    protected function compareMultiple($method, array $keys, $destinationKey = null)
    {
        foreach($keys as &$key) {
            $key = (string)$key;
        }

        array_unshift($keys, $this->getName());

        if ($destinationKey !== null) {
            array_unshift($keys, (string)$destinationKey);
        }

        return call_user_func_array(array($this->getRedis(), $method), $keys);
    }
}
