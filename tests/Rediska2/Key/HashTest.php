<?php

namespace Rediska2Test\Key;

use Rediska2\Manager,
    Rediska2\Key\Hash;

class HashTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Hash
     */
    protected $hash;

    public function setUp()
    {
        $this->hash = new Hash('test');
    }

    public function testRemove()
    {
        $this->markTestIncomplete();
//        $arguments = array_merge(array($this->getName()), $fields);
//        return call_user_func_array(array($this->getRedis(), 'hDel'), $arguments);
    }

    public function testHas()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hExists($this->getName(), $field);
    }

    public function testSet()
    {
        $this->markTestIncomplete();
//        if ($overwrite) {
//            return $this->getRedis()->hSet($this->getName(), $field, $value);
//        } else {
//            return $this->getRedis()->hSetNx($this->getName(), $field, $value);
//        }
    }

    public function testSetMultiple()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hMSet($this->getName(), $fieldsAndValue);
    }

    public function testGet()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hGet($this->getName(), $field);
    }

    public function testGetMultiple()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hMGet($this->getName(), $fields);
    }

    public function testGetAll()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hGetAll($this->getName());
    }

    public function testGetFields()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hKeys($this->getName());
    }

    public function testGetValues()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hVals($this->getName());
    }

    public function testGetLength()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hLen($this->getName());
    }

    public function testIncrement()
    {
        $this->markTestIncomplete();
//        if (is_float($amount)) {
//            return $this->getRedis()->hIncrByFloat($this->getName(), $field, $amount);
//        } else {
//            return $this->getRedis()->hIncrBy($this->getName(), $field, $amount);
//        }
    }

    /**
     * Implements field object access
     */

    public function testMagicSet()
    {
        $this->markTestIncomplete();
//        return $this->set($field, $value);
    }

    public function testMagicGet()
    {
        $this->markTestIncomplete();
//        return $this->get($field);
    }

    public function testMagicIsset()
    {
        $this->markTestIncomplete();
//        return $this->has($field);
    }

    public function testMagicUnset()
    {
        $this->markTestIncomplete();
//        return $this->remove(array($field));
    }

    public function testCount()
    {
        $this->markTestIncomplete();
//        return $this->getLength();
    }
}
