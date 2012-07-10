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
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->assertTrue($this->hash->has('test'));
    }

    public function testGet()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->assertEquals(1, $this->hash->get('test'));
    }

    public function testGetMultiple()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);

        $this->assertEquals(array('test' => 1, 'test2' => 2), $this->hash->getMultiple(array('test', 'test2')));
    }

    public function testGetAll()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);

        $this->assertEquals(array('test' => 1, 'test2' => 2), $this->hash->getAll());
    }

    public function testGetFields()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);

        $this->assertEquals(array('test', 'test2'), $this->hash->getFields());
    }

    public function testGetValues()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);

        $this->assertEquals(array(1, 2), $this->hash->getValues());
    }

    public function testGetLength()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);

        $this->assertEquals(2, $this->hash->getLength());
    }

    public function testRemove()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);

        $this->assertEquals(1, $this->hash->remove('test'));

        $this->assertFalse($this->hash->getRedis()->hExists('test', 'test'));
        $this->assertTrue($this->hash->getRedis()->hExists('test', 'test2'));
    }

    public function testRemoveMultiple()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);
        $this->hash->getRedis()->hSet('test', 'test3', 3);

        $this->assertEquals(2, $this->hash->removeMultiple(array('test', 'test2')));

        $this->assertFalse($this->hash->getRedis()->hExists('test', 'test'));
        $this->assertFalse($this->hash->getRedis()->hExists('test', 'test2'));
        $this->assertTrue($this->hash->getRedis()->hExists('test', 'test3'));
    }

    public function testIncrement()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);

        $this->assertEquals(2, $this->hash->increment('test'));

        $this->assertEquals(2, $this->hash->getRedis()->hget('test', 'test'));
    }

    /**
     * Implements field object access
     */

    public function testMagicSet()
    {
        $this->hash->test = 1;
        $this->assertEquals(1, $this->hash->getRedis()->hGet('test', 'test'));
    }

    public function testMagicGet()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->assertEquals(1, $this->hash->test);
    }

    public function testMagicIsset()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->assertTrue(isset($this->hash->test));
        $this->assertFalse(isset($this->hash->test2));
    }

    public function testMagicUnset()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);

        unset($this->hash->test);

        $this->assertFalse($this->hash->getRedis()->hExists('test', 'test'));
        $this->assertTrue($this->hash->getRedis()->hExists('test', 'test2'));
    }

    public function testCount()
    {
        $this->hash->getRedis()->hSet('test', 'test', 1);
        $this->hash->getRedis()->hSet('test', 'test2', 2);

        $this->assertEquals(2, count($this->hash));
    }

    public function testGetIterator()
    {
        $fields = array(
            'test'  => 1,
            'test2' => 2,
            'test3' => 3
        );

        $this->hash->getRedis()->hMset('test', $fields);

        $count = 0;
        reset($fields);
        foreach($this->hash as $field => $value) {
            $this->assertEquals(key($fields), $field);
            $this->assertEquals(current($fields), $value);
            next($fields);
            $count++;
        }

        $this->assertEquals(3, $count);
    }
}
