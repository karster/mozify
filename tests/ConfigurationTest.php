<?php

namespace karster\image\tests;

use karster\image\Configuration;

class ConfigurationTest extends TestCase
{
    public function testCreateConfiguration()
    {
        $configuration = new Configuration([
            'width' => 300,
            'height' => 200,
            'colorDepth' => 2,
            'searchWindow' => 10,
            'imageSrc' => 'image.jpg'
        ]);

        $this->assertFalse($configuration->test);
        $this->assertSame(300, $configuration->width);
        $this->assertSame(200, $configuration->height);
        $this->assertSame(4, $configuration->colorDepth);
        $this->assertSame('image.jpg', $configuration->imageSrc);
        $this->assertSame(10, $configuration->searchWindow);
    }
}