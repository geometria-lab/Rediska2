<?php

namespace Rediska2Test;

use Rediska2\Manager;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        $manager = Manager::getInstance();
        $manager->get('default')->flushAll();
    }
}