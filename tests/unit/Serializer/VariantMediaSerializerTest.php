<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterVariantMedia;
use Twitter\Serializer\TwitterVariantMediaSerializer;
use Twitter\TwitterSerializable;

class VariantMediaSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $contentType;

    /** @var string */
    private $url;

    /** @var int */
    private $bitrate;

    /** @var TwitterVariantMediaSerializer */
    private $serializer;

    public function setUp()
    {
        $this->contentType = 'text/html';
        $this->url = 'http://www.simple.com';
        $this->bitrate = 1024;

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
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->serialize($this->getInvalidObject());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $serialized = $this->serializer->serialize($this->getValidObject());

        $this->assertEquals($this->url, $serialized->url);
        $this->assertEquals($this->contentType, $serialized->content_type);
        $this->assertEquals($this->bitrate, $serialized->bitrate);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $variantMedia = $this->serializer->unserialize($this->getValidSerializedObject());

        $this->assertEquals($this->url, $variantMedia->getUrl());
        $this->assertEquals($this->bitrate, $variantMedia->getBitrate());
        $this->assertEquals($this->contentType, $variantMedia->getContentType());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = $this->getInvalidSerializedObject();

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

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return TwitterVariantMedia
     */
    private function getValidObject()
    {
        return TwitterVariantMedia::create(
            $this->contentType,
            $this->url,
            $this->bitrate
        );
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        $variantMediaObj = new \stdClass();
        $variantMediaObj->url = $this->url;
        $variantMediaObj->bitrate = $this->bitrate;
        $variantMediaObj->content_type = $this->contentType;
        return $variantMediaObj;
    }

    /**
     * @return \stdClass
     */
    private function getInvalidSerializedObject()
    {
        return new \stdClass();
    }
}
