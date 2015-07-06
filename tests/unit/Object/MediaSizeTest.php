<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterMediaSize;

class MediaSizeTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $name = 'video';
        $width = 800;
        $height = 600;
        $resize = 'no';

        $mediaSize = new TwitterMediaSize($name, $width, $height, $resize);

        $this->assertEquals($name, $mediaSize->getName());
        $this->assertEquals($width, $mediaSize->getWidth());
        $this->assertEquals($height, $mediaSize->getHeight());
        $this->assertEquals($resize, $mediaSize->getResize());
    }
} 