<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterSerializable;

class MediaSizeSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /** @var TwitterMediaSizeSerializer */
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
    public function itShouldNotSerializeWithIllegalObject()
    {
        $object = \Mockery::mock(TwitterSerializable::class);

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
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
    public function itShouldUnserialize()
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

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterMediaSizeSerializer::build();

        $this->assertInstanceOf(TwitterMediaSizeSerializer::class, $serializer);
    }
}
