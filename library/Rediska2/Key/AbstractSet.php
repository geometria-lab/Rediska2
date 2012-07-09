<?php

namespace Rediska2\Key;

abstract class AbstractSet extends AbstractKey
{
    public function sort(array $options = array())
    {
        $this->getRedis()->sort($this->getName(), $options);
    }
}