<?php

namespace Rediska2\Key;

abstract class AbstractSet extends AbstractKey implements \Countable, \IteratorAggregate
{
    /**
     * Sort
     *
     * @param array $options array(key => value, ...) - optional, with the following keys and values:
     * - 'by' => 'some_pattern_*',
     * - 'limit' => array(0, 1),
     * - 'get' => 'some_other_pattern_*' or an array of patterns,
     * - 'sort' => 'asc' or 'desc',
     * - 'alpha' => TRUE,
     * - 'store' => 'external-key'
     * @return array
     */
    public function sort(array $options = array())
    {
        return $this->getRedis()->sort($this->getName(), $options);
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