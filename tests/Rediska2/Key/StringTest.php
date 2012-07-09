<?php

namespace Rediska2Test\Key;

use Rediska2\Manager,
    Rediska2\Key\String;

class StringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rediska2\Key\String
     */
    protected $key;

    public function setUp()
    {
        $this->key = new String('test');
    }

    public function tearDown()
    {
        $manager = Manager::getInstance();
        $manager->get('default')->flushAll();
    }

    public function testDelete()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertEquals(1, $this->key->delete());
        $this->assertFalse($this->key->getRedis()->get('test'));
    }

    public function testIsExists()
    {
        $this->assertFalse($this->key->isExists());
        $this->key->getRedis()->set('test', 123);
        $this->assertTrue($this->key->isExists());
    }

    public function testGetType()
    {
        $this->assertEquals(0, $this->key->getType());
        $this->key->getRedis()->set('test', 123);
        $this->assertEquals(1, $this->key->getType());
    }

    public function testRename()
    {
        $this->key->getRedis()->set('test', 123);
        $this->key->rename('test2', 123);
        $this->assertFalse($this->key->getRedis()->exists('test'));
        $this->assertEquals(123, $this->key->getRedis()->set('test2', 123));
        $this->assertEquals('test2', $this->key->getName());
    }

    public function testMoveToDb()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertTrue($this->key->moveToDb(2));
        $this->assertFalse($this->key->getRedis()->exists('test'));
        $this->key->getRedis()->select(2);
        $this->assertTrue($this->key->getRedis()->exists('test'));
    }

    public function testExpire()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertTrue($this->key->expire(1));
        sleep(2);
        $this->assertFalse($this->key->getRedis()->exists('test'));
    }

    public function testExpireAt()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertTrue($this->key->expireAt(time() + 1));
        sleep(2);
        $this->assertFalse($this->key->getRedis()->exists('test'));
    }

    public function testGetLifetime()
    {
        $this->key->getRedis()->set('test', 123);
        $this->key->getRedis()->expireAt('test', time() + 10);
        $this->assertEquals(10, $this->key->getLifetime());
    }

    public function testPersist()
    {
        $this->key->getRedis()->set('test', 123);
        $this->key->getRedis()->expireAt('test', time() + 10);
        $this->assertNotEquals(-1, $this->key->getRedis()->ttl('test'));
        $this->key->persist();
        $this->assertEquals(-1, $this->key->getRedis()->ttl('test'));
    }

    public function testSetValue()
    {
        $this->assertTrue($this->key->setValue(123));

        $this->assertEquals(123, $this->key->getRedis()->get('test'));
        $this->assertFalse($this->key->setValue(234, false));
        $this->assertEquals(123, $this->key->getRedis()->get('test'));
    }

    public function testGetValue()
    {
        $this->assertNull($this->key->getValue());

        $this->key->getRedis()->set('test', 123);

        $this->assertEquals(123, $this->key->getValue());
    }

    public function testGetAndSetValue()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertEquals(123, $this->key->getAndSetValue(456));
        $this->assertEquals(456, $this->key->getRedis()->get('test'));
    }

    public function testSetAndExpire()
    {
        $this->key->setAndExpire(123, 5);
        $this->assertNotEquals(-1, $this->key->getRedis()->ttl('test'));
    }

    public function testAppend()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertEquals(6, $this->key->append(456));
        $this->assertEquals(123456, $this->key->getRedis()->get('test'));
    }

    public function testSetBit()
    {
        $this->assertEquals(0, $this->key->setBit(3, 1));
        $this->assertEquals(1, $this->key->getRedis()->getBit('test', 3));
    }

    public function testGetBit()
    {
        $this->key->getRedis()->setBit('test', 3, 1);
        $this->assertEquals(1, $this->key->getBit(3));
    }

    public function testSetRange()
    {
        $this->key->getRedis()->set('test', 12456);
        $this->assertEquals(6, $this->key->setRange(2, 3456));
        $this->assertEquals(123456, $this->key->getRedis()->get('test'));
    }

    public function testGetRange()
    {
        $this->key->getRedis()->set('test', 123456);
        $this->assertEquals(3456, $this->key->getRange(2, -1));
    }

    public function testGetLength()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertEquals(3, $this->key->getLength());
    }

    public function testIncrement()
    {
        $this->key->increment();
        $this->assertEquals(1, $this->key->getRedis()->get('test'));
        $this->key->increment(2);
        $this->assertEquals(3, $this->key->getRedis()->get('test'));
    }

    public function testDecrement()
    {
        $this->key->getRedis()->set('test', 4);
        $this->key->decrement();
        $this->assertEquals(3, $this->key->getRedis()->get('test'));
        $this->key->decrement(2);
        $this->assertEquals(1, $this->key->getRedis()->get('test'));
    }

    public function testToString()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertEquals("123", "{$this->key}");
    }

    public function testCount()
    {
        $this->key->getRedis()->set('test', 123);
        $this->assertEquals(3, count($this->key));
    }

    public function testOffsetExists()
    {
        $this->key->getRedis()->setBit('test', 3, 1);
        $this->assertTrue(isset($this->key[3]));
        $this->assertFalse(isset($this->key[2]));
    }

    public function testOffsetGet()
    {
        $this->key->getRedis()->setBit('test', 3, 1);
        $this->assertEquals(1, $this->key[3]);
        $this->assertEquals(0, $this->key[4]);
    }

    public function testOffsetSet()
    {
        $this->key[3] = 1;
        $this->assertEquals(1, $this->key->getRedis()->getBit('test', 3));
    }

    public function testOffsetUnset()
    {
        $this->key->getRedis()->setBit('test', 3, 1);
        unset($this->key[3]);
        $this->assertEquals(0, $this->key->getRedis()->getBit('test', 3));
    }
}
