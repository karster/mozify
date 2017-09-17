<?php

namespace karster\image\tests;

use karster\image\Configuration;
use karster\image\Wrapper;

class WrapperTest extends TestCase
{
    /**
     * @var Wrapper
     */
    private $wrapper;

    public function setUp()
    {
        $configuration = new Configuration([
            'width' => 300,
            'height' => 200
        ]);

        $this->wrapper = new Wrapper($configuration);
    }

    public function testBegin()
    {
        $result = $this->wrapper->begin();

        $this->assertNotEmpty($result);
        $this->assertContains('width="300"', $result);
    }

    public function testEnd()
    {
        $result = $this->wrapper->end();

        $this->assertNotEmpty($result);
        $this->assertContains('</td>
                </tr>
            </tbody>
         </table>', $result);
    }
}