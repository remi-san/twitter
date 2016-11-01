<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterVariantMediaSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterSerializable;

class VariantMediaSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /** @var TwitterVariantMediaSerializer */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterVariantMediaSerializer();
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
        $url = 'http://www.simple.com';
        $contentType = 'text/html';
        $bitrate = 1024;

        $obj = $this->getVariantMedia();
        $obj->shouldReceive('getContentType')->andReturn($contentType);
        $obj->shouldReceive('getUrl')->andReturn($url);
        $obj->shouldReceive('getBitrate')->andReturn($bitrate);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($url, $serialized->url);
        $this->assertEquals($contentType, $serialized->content_type);
        $this->assertEquals($bitrate, $serialized->bitrate);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $variantMediaObj = new \stdClass();
        $variantMediaObj->url = 'http://video.url/a.mpg';
        $variantMediaObj->bitrate = 320;
        $variantMediaObj->content_type = 'video/mpeg';

        $variantMedia = $this->serializer->unserialize($variantMediaObj);

        $this->assertEquals($variantMediaObj->url, $variantMedia->getUrl());
        $this->assertEquals($variantMediaObj->bitrate, $variantMedia->getBitrate());
        $this->assertEquals($variantMediaObj->content_type, $variantMedia->getContentType());
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
        $serializer = TwitterVariantMediaSerializer::build();

        $this->assertInstanceOf(TwitterVariantMediaSerializer::class, $serializer);
    }
}
