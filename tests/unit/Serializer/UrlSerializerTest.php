<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterUrlSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class UrlSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterUrlSerializer
     */
    private $serializer;

    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->serializer = new TwitterUrlSerializer($this->entityIndicesSerializer);
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
        $obj = $this->getUrl();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $urlObj = new \stdClass();
        $urlObj->url = 'http://ur.l';
        $urlObj->display_url = 'http://display.url';
        $urlObj->expanded_url = 'http://expanded.url';
        $urlObj->indices = array(42, 666);

        $indices = new TwitterEntityIndices(42, 666);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $url = $this->serializer->unserialize($urlObj);

        $this->assertEquals($urlObj->url, $url->getUrl());
        $this->assertEquals($urlObj->display_url, $url->getDisplayUrl());
        $this->assertEquals($urlObj->expanded_url, $url->getExpandedUrl());
        $this->assertEquals($indices, $url->getIndices());
    }
} 