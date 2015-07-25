<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class MediaSizeSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterMediaSizeSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterMediaSizeSerializer();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testSerializeWithIllegalObject()
    {
        $user = $this->getTwitterUser(42, 'douglas');

        $this->setExpectedException('\\InvalidArgumentException');

        $this->serializer->serialize($user);
    }

    /**
     * @test
     */
    public function testSerializeWithLegalObject()
    {
        $w = 1920;
        $h = 1080;
        $resize = false;

        $obj = $this->getTwitterMediaSize();
        $obj->shouldReceive('getWidth')->andReturn($w);
        $obj->shouldReceive('getHeight')->andReturn($h);
        $obj->shouldReceive('getResize')->andReturn($resize);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($w, $serialized->w);
        $this->assertEquals($h, $serialized->h);
        $this->assertEquals($resize, $serialized->resize);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $sizeName = '1080p';

        $mediaSizeObj = new \stdClass();
        $mediaSizeObj->w = 1920;
        $mediaSizeObj->h = 1080;
        $mediaSizeObj->resize = false;

        $mediaSize = $this->serializer
            ->unserialize($mediaSizeObj, array(TwitterMediaSizeSerializer::NAME_VAR => $sizeName));

        $this->assertEquals($sizeName, $mediaSize->getName());
        $this->assertEquals($mediaSizeObj->resize, $mediaSize->getResize());
        $this->assertEquals($mediaSizeObj->w, $mediaSize->getWidth());
        $this->assertEquals($mediaSizeObj->h, $mediaSize->getHeight());
    }
}
