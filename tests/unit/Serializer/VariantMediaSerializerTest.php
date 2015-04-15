<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterVariantMediaSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class VariantMediaSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterVariantMediaSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterVariantMediaSerializer();
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
        $obj = $this->getVariantMedia();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
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
} 