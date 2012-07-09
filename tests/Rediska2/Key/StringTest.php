<?php

namespace Rediska2Test\Key;

use Rediska2\Manager,
    Rediska2\Key\String;

class StringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rediska2\Key\String
     */
    protected $string;

    public function setUp()
    {
        $this->string = new String('test');
    }

    public function tearDown()
    {
        $manager = Manager::getInstance();
        $manager->get('default')->flushAll();
    }

    public function testDelete()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertEquals(1, $this->string->delete());
        $this->assertFalse($this->string->getRedis()->get('test'));
    }

    public function testIsExists()
    {
        $this->assertFalse($this->string->isExists());
        $this->string->getRedis()->set('test', 123);
        $this->assertTrue($this->string->isExists());
    }

    public function testGetType()
    {
        $this->assertEquals(0, $this->string->getType());
        $this->string->getRedis()->set('test', 123);
        $this->assertEquals(1, $this->string->getType());
    }

    public function testRename()
    {
        $this->string->getRedis()->set('test', 123);
        $this->string->rename('test2', 123);
        $this->assertFalse($this->string->getRedis()->exists('test'));
        $this->assertEquals(123, $this->string->getRedis()->set('test2', 123));
        $this->assertEquals('test2', $this->string->getName());
    }

    public function testMoveToDb()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertTrue($this->string->moveToDb(2));
        $this->assertFalse($this->string->getRedis()->exists('test'));
        $this->string->getRedis()->select(2);
        $this->assertTrue($this->string->getRedis()->exists('test'));
    }

    public function testExpire()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertTrue($this->string->expire(1));
        sleep(2);
        $this->assertFalse($this->string->getRedis()->exists('test'));
    }

    public function testExpireAt()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertTrue($this->string->expireAt(time() + 1));
        sleep(2);
        $this->assertFalse($this->string->getRedis()->exists('test'));
    }

    public function testGetLifetime()
    {
        $this->string->getRedis()->set('test', 123);
        $this->string->getRedis()->expireAt('test', time() + 10);
        $this->assertEquals(10, $this->string->getLifetime());
    }

    public function testPersist()
    {
        $this->string->getRedis()->set('test', 123);
        $this->string->getRedis()->expireAt('test', time() + 10);
        $this->assertNotEquals(-1, $this->string->getRedis()->ttl('test'));
        $this->string->persist();
        $this->assertEquals(-1, $this->string->getRedis()->ttl('test'));
    }

    public function testSetValue()
    {
        $this->assertTrue($this->string->setValue(123));

        $this->assertEquals(123, $this->string->getRedis()->get('test'));
        $this->assertFalse($this->string->setValue(234, false));
        $this->assertEquals(123, $this->string->getRedis()->get('test'));
    }

    public function testGetValue()
    {
        $this->assertNull($this->string->getValue());

        $this->string->getRedis()->set('test', 123);

        $this->assertEquals(123, $this->string->getValue());
    }

    public function testGetAndSetValue()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertEquals(123, $this->string->getAndSetValue(456));
        $this->assertEquals(456, $this->string->getRedis()->get('test'));
    }

    public function testSetAndExpire()
    {
        $this->string->setAndExpire(123, 5);
        $this->assertNotEquals(-1, $this->string->getRedis()->ttl('test'));
    }

    public function testAppend()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertEquals(6, $this->string->append(456));
        $this->assertEquals(123456, $this->string->getRedis()->get('test'));
    }

    public function testSetBit()
    {
        $this->assertEquals(0, $this->string->setBit(3, 1));
        $this->assertEquals(1, $this->string->getRedis()->getBit('test', 3));
    }

    public function testGetBit()
    {
        $this->string->getRedis()->setBit('test', 3, 1);
        $this->assertEquals(1, $this->string->getBit(3));
    }

    public function testSetRange()
    {
        $this->string->getRedis()->set('test', 12456);
        $this->assertEquals(6, $this->string->setRange(2, 3456));
        $this->assertEquals(123456, $this->string->getRedis()->get('test'));
    }

    public function testGetRange()
    {
        $this->string->getRedis()->set('test', 123456);
        $this->assertEquals(3456, $this->string->getRange(2, -1));
    }

    public function testGetLength()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertEquals(3, $this->string->getLength());
    }

    public function testIncrement()
    {
        $this->string->increment();
        $this->assertEquals(1, $this->string->getRedis()->get('test'));
        $this->string->increment(2);
        $this->assertEquals(3, $this->string->getRedis()->get('test'));
    }

    public function testDecrement()
    {
        $this->string->getRedis()->set('test', 4);
        $this->string->decrement();
        $this->assertEquals(3, $this->string->getRedis()->get('test'));
        $this->string->decrement(2);
        $this->assertEquals(1, $this->string->getRedis()->get('test'));
    }

    public function testToString()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertEquals("123", "{$this->string}");
    }

    public function testCount()
    {
        $this->string->getRedis()->set('test', 123);
        $this->assertEquals(3, count($this->string));
    }

    public function testOffsetExists()
    {
        $this->string->getRedis()->setBit('test', 3, 1);
        $this->assertTrue(isset($this->string[3]));
        $this->assertFalse(isset($this->string[2]));
    }

    public function testOffsetGet()
    {
        $this->string->getRedis()->setBit('test', 3, 1);
        $this->assertEquals(1, $this->string[3]);
        $this->assertEquals(0, $this->string[4]);
    }

    public function testOffsetSet()
    {
        $this->string[3] = 1;
        $this->assertEquals(1, $this->string->getRedis()->getBit('test', 3));
    }

    public function testOffsetUnset()
    {
        $this->string->getRedis()->setBit('test', 3, 1);
        unset($this->string[3]);
        $this->assertEquals(0, $this->string->getRedis()->getBit('test', 3));
    }
}
