<?php

namespace karster\image\tests;

use karster\image\Mozify;

class MozifyTest extends TestCase
{
    /**
     * @var Mozify
     */
    private $mozify;

    public function setUp()
    {
        $this->mozify = new Mozify();
    }

    public function testCreateConfiguration()
    {
        $this->invokeMethod($this->mozify, 'createConfiguration');

    }
}