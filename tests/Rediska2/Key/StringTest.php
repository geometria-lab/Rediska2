<?php

namespace Rediska2Test\Key;

use Rediska2\Manager,
    Rediska2\Key\String;

class StringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var String
     */
    protected $key;

    public function setUp()
    {
        $this->key = new String('test');
    }

    public function testSetValue()
    {
        $this->assertTrue($this->key->setValue(123));

        $this->assertEquals(123, $this->key->getRedis()->get('test'));
    }

    public function testGetValue()
    {
        $this->key->getRedis()->get('test');

        $this->assertEquals(123, $this->key->getValue());
    }
}
