<?php

namespace Rediska2Test\Key;

use Rediska2Test\TestCase;

use Rediska2\Key\Hash;

class HashTest extends TestCase
{
    /**
     * @var Hash
     */
    protected $hash;

    public function setUp()
    {
        $this->hash = new Hash('test');
    }

    public function testSet()
    {
        $this->assertEquals(1, $this->hash->set('test', 1));
        $this->assertEquals(1, $this->hash->getRedis()->hGet('test', 'test'));
        $this->assertEquals(0, $this->hash->set('test', 2, false));
        $this->assertEquals(1, $this->hash->getRedis()->hGet('test', 'test'));
    }

    public function testSetMultiple()
    {
        $this->assertEquals(1, $this->hash->setMultiple(array('test' => 1, 'test2' => 2)));
        $this->assertEquals(1, $this->hash->getRedis()->hGet('test', 'test'));
        $this->assertEquals(2, $this->hash->getRedis()->hGet('test', 'test2'));
    }

    public function testHas()
    {
        $this->markTestIncomplete();
//        return $this->getRedis()->hExists($this->getName(), $field);
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

    public function testRemove()
    {
        $this->markTestIncomplete();
//        $arguments = array_merge(array($this->getName()), $fields);
//        return call_user_func_array(array($this->getRedis(), 'hDel'), $arguments);
    }

    public function testRemoveMultiple()
    {
        $this->markTestIncomplete();
//        $arguments = array_merge(array($this->getName()), $fields);
//        return call_user_func_array(array($this->getRedis(), 'hDel'), $arguments);
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
