<?php

namespace karster\image\tests;

use karster\image\Configuration;
use karster\image\MsoHack;

class MsoHackTest extends TestCase
{
    /**
     * @var MsoHack
     */
    private $msoHack;

    public function setUp()
    {
        $configuration = new Configuration([
            'width' => 300,
            'height' => 200
        ]);

        $this->msoHack = new MsoHack($configuration);
    }

    public function testBegin()
    {
        $result = $this->msoHack->begin();

        $this->assertNotEmpty($result);
        $this->assertContains('width="300"', $result);
        $this->assertContains('height="200"', $result);
    }

    public function testEnd()
    {
        $result = $this->msoHack->end();

        $this->assertNotEmpty($result);
        $this->assertContains('<!--[if mso]>*/</style><![endif]-->', $result);
    }
}