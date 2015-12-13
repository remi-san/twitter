<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterVariantMediaSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class VariantMediaSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterVariantMediaSerializer
     */
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
    public function testUnserialize()
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
    public function testStaticBuilder()
    {
        $serializer = TwitterVariantMediaSerializer::build();

        $this->assertInstanceOf(TwitterVariantMediaSerializer::class, $serializer);
    }
}
