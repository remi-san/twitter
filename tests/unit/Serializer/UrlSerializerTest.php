<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterUrlSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterSerializable;

class UrlSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /** @var TwitterEntityIndicesSerializer | Mock */
    private $entityIndicesSerializer;

    /** @var TwitterUrlSerializer */
    private $serializer;

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->serializer = new TwitterUrlSerializer($this->entityIndicesSerializer);
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
        $displayUrl = 'http://www.display.com';
        $expandedUrl = 'http://www.expanded.com';

        $indices = $this->getIndices();
        $indicesObj = new \stdClass();
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($indices)->andReturn($indicesObj);

        $obj = $this->getUrl();
        $obj->shouldReceive('getUrl')->andReturn($url);
        $obj->shouldReceive('getDisplayUrl')->andReturn($displayUrl);
        $obj->shouldReceive('getExpandedUrl')->andReturn($expandedUrl);
        $obj->shouldReceive('getIndices')->andReturn($indices);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($url, $serialized->url);
        $this->assertEquals($displayUrl, $serialized->display_url);
        $this->assertEquals($expandedUrl, $serialized->expanded_url);
        $this->assertEquals($indicesObj, $serialized->indices);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $urlObj = new \stdClass();
        $urlObj->url = 'http://ur.l';
        $urlObj->display_url = 'http://display.url';
        $urlObj->expanded_url = 'http://expanded.url';
        $urlObj->indices = array(42, 666);

        $indices = TwitterEntityIndices::create(42, 666);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $url = $this->serializer->unserialize($urlObj);

        $this->assertEquals($urlObj->url, $url->getUrl());
        $this->assertEquals($urlObj->display_url, $url->getDisplayUrl());
        $this->assertEquals($urlObj->expanded_url, $url->getExpandedUrl());
        $this->assertEquals($indices, $url->getIndices());
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
        $serializer = TwitterUrlSerializer::build();

        $this->assertInstanceOf(TwitterUrlSerializer::class, $serializer);
    }
}
