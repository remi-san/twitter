<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterUrl;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterUrlSerializer;
use Twitter\TwitterSerializable;

class UrlSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $url;

    /** @var string */
    private $displayUrl;

    /** @var string */
    private $expandedUrl;

    /** @var TwitterEntityIndices */
    private $indices;

    /** @var array */
    private $indicesObj;

    /** @var TwitterEntityIndicesSerializer | Mock */
    private $entityIndicesSerializer;


    /** @var TwitterUrlSerializer */
    private $serializer;

    public function setUp()
    {
        $this->url = 'http://www.simple.com';
        $this->displayUrl = 'http://www.display.com';
        $this->expandedUrl = 'http://www.expanded.com';
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);

        $this->indicesObj = [];

        $this->entityIndicesSerializer = \Mockery::mock(TwitterEntityIndicesSerializer::class);

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
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->serialize($this->getInvalidObject());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $this->givenIndicesSerializerWillSerializeIndices();

        $serialized = $this->serializer->serialize($this->getValidObject());

        $this->assertEquals($this->url, $serialized->url);
        $this->assertEquals($this->displayUrl, $serialized->display_url);
        $this->assertEquals($this->expandedUrl, $serialized->expanded_url);
        $this->assertEquals($this->indicesObj, $serialized->indices);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->givenIndicesSerializerWillUnserializeIndices();

        $url = $this->serializer->unserialize($this->getValidSerializedObject());

        $this->assertEquals($this->url, $url->getUrl());
        $this->assertEquals($this->displayUrl, $url->getDisplayUrl());
        $this->assertEquals($this->expandedUrl, $url->getExpandedUrl());
        $this->assertEquals($this->indices, $url->getIndices());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($this->getInvalidSerializedObject());
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterUrlSerializer::build();

        $this->assertInstanceOf(TwitterUrlSerializer::class, $serializer);
    }

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return TwitterUrl
     */
    private function getValidObject()
    {
        return TwitterUrl::create($this->url, $this->displayUrl, $this->expandedUrl, $this->indices);
    }

    private function givenIndicesSerializerWillSerializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($this->indices)->andReturn($this->indicesObj);
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        $urlObj = new \stdClass();
        $urlObj->url = $this->url;
        $urlObj->display_url = $this->displayUrl;
        $urlObj->expanded_url = $this->expandedUrl;
        $urlObj->indices = $this->indicesObj;
        return $urlObj;
    }

    private function givenIndicesSerializerWillUnserializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($this->indices);
    }

    /**
     * @return \stdClass
     */
    private function getInvalidSerializedObject()
    {
        return new \stdClass();
    }
}
