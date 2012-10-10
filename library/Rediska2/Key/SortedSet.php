<?php

namespace Rediska2\Key;

class SortedSet extends AbstractSet
{
    public function add($member, $score)
    {
        return $this->getRedis()->zAdd($this->getName(), $score, $member);
    }

    public function addMultiple(array $members)
    {
        $args = array($this->getName());
        foreach($members as $member => $score) {
            array_push($args, $score, $member);
        }

        return call_user_func_array(array($this->getRedis(), 'zAdd'), $args);
    }

    public function getCountByScore($min, $max)
    {
        return $this->getRedis()->zCount($this->getName(), $min, $max);
    }

    public function increment($member, $amount = 1.0)
    {
        return $this->getRedis()->zIncrBy($this->getName(), $amount, $member);
    }

    public function getLength()
    {
        return $this->getRedis()->zCard($this->getName());
    }

    public function storeIntersection($keys, $destinationKey, $aggregateFunction = 'SUM')
    {
        return $this->storeCompared('zInter', $keys, $destinationKey, $aggregateFunction);
    }

    public function getRange($start, $stop, $withScores = false)
    {
        return $this->getRedis()->zRange($this->getName(), $start, $stop, $withScores);
    }

    public function getRangeReverted($start, $stop, $withScores = false)
    {
        return $this->getRedis()->zRevRange($this->getName(), $start, $stop, $withScores);
    }

    public function getRangeByScore($min, $max, $withScores = false, $limit = null, $offset = null)
    {
        $options = array();

        if ($withScores) {
            $options['withscores'] = true;
        }

        if ($limit !== null || $offset !== null) {
            $options['limit'] = array($offset, $limit);
        }

        return $this->getRedis()->zRangeByScore($this->getName(), $min, $max, $options);
    }

    public function getRangeByScoreReverted($min, $max, $withScores = false, $limit = null, $offset = null)
    {
        $options = array();

        if ($withScores) {
            $options['withscores'] = true;
        }

        if ($limit !== null || $offset !== null) {
            $options['limit'] = array($offset, $limit);
        }

        return $this->getRedis()->zRevRangeByScore($this->getName(), $min, $max, $options);
    }

    public function getRank($member)
    {
        return $this->getRedis()->zRank($this->getName(), $member);
    }

    public function getRankReverted($member)
    {
        return $this->getRedis()->zRevRank($this->getName(), $member);
    }

    public function remove($member)
    {
        return $this->getRedis()->zRem($this->getName(), $member);
    }

    public function removeMultiple(array $members)
    {
        $arguments = array_merge(array($this->getName()), $members);
        return call_user_func_array(array($this->getRedis(), 'zRem'), $arguments);
    }

    public function removeRangeByRank($start, $stop)
    {
        return $this->getRedis()->zRemRangeByRank($this->getName(), $start, $stop);
    }

    public function removeRangeByScore($min, $max)
    {
        return $this->getRedis()->zRemRangeByScore($this->getName(), $min, $max);
    }

    public function getScore($member)
    {
        return $this->getRedis()->zScore($this->getName(), $member);
    }

    public function storeUnion($keys, $destinationKey, $aggregateFunction = 'SUM')
    {
        return $this->storeCompared('zUnion', $keys, $destinationKey, $aggregateFunction);
    }

    protected function storeCompared($method, $keys, $destinationKey, $aggregateFunction)
    {
        reset($keys);
        $keysWithWeights = !is_integer(key($keys));

        $sets = array();
        $weights = $keysWithWeights ? array() : null;

        if ($keysWithWeights) {
            if (!isset($keys[$this->getName()])) {
                $sets[] = $this->getName();
                $weights[] = 1;
            }
        } else {
            if (!in_array($this->getName(), $keys)) {
                $sets[] = $this->getName();
            }
        }

        foreach($keys as $index => $value) {
            if ($keysWithWeights) {
                array_push($sets, $index);
                array_push($weights, $value);
            } else {
                array_push($sets, $value);
            }
        }

        return call_user_func(array($this->getRedis(), $method), (string)$destinationKey, $sets, $weights, $aggregateFunction);
    }
}
