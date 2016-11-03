<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterMedia;
use Twitter\Object\TwitterMediaSize;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterMediaSerializer;
use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\TwitterSerializable;

class MediaSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $id;

    /** @var string */
    private $mediaUrl;

    /** @var string */
    private $mediaUrlHttps;

    /** @var string */
    private $url;

    /** @var string */
    private $displayUrl;

    /** @var string */
    private $expandedUrl;

    /** @var string */
    private $type;

    /** @var string */
    private $sizeName;


    /** @var TwitterMediaSize | Mock */
    private $mediaSize;

    /** @var TwitterEntityIndices | Mock */
    private $indices;


    /** @var object */
    private $serializedSize;

    /** @var int[] */
    private $serializedIndices;


    /** @var TwitterEntityIndicesSerializer | Mock */
    private $entityIndicesSerializer;

    /** @var TwitterMediaSizeSerializer | Mock */
    private $mediaSizeSerializer;

    /** @var TwitterMediaSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $this->id = 42;
        $this->mediaUrl = 'http://media.url';
        $this->mediaUrlHttps = 'https://media.url';
        $this->url = 'http://ur.l';
        $this->displayUrl = 'http://display.url';
        $this->expandedUrl = 'http://expanded.url';
        $this->type = 'type';
        $this->sizeName = 'sizeName';

        $this->mediaSize = \Mockery::mock(TwitterMediaSize::class);
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);

        $this->serializedSize = new \stdClass();
        $this->serializedIndices = [];

        $this->entityIndicesSerializer = \Mockery::mock(TwitterEntityIndicesSerializer::class);
        $this->mediaSizeSerializer = \Mockery::mock(TwitterMediaSizeSerializer::class);

        $this->serviceUnderTest = new TwitterMediaSerializer(
            $this->entityIndicesSerializer,
            $this->mediaSizeSerializer
        );
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

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $this->mediaSize->shouldReceive('getName')->andReturn($this->sizeName);
        $this->mediaSizeSerializer->shouldReceive('serialize')->with($this->mediaSize)->andReturn($this->serializedSize);
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($this->indices)->andReturn($this->serializedIndices);

        $obj = TwitterMedia::create(
            $this->id,
            $this->mediaUrl,
            $this->mediaUrlHttps,
            $this->url,
            $this->displayUrl,
            $this->expandedUrl,
            [$this->mediaSize],
            $this->type,
            $this->indices
        );

        $serialized = $this->serviceUnderTest->serialize($obj);

        $this->assertEquals($this->id, $serialized->id);
        $this->assertEquals($this->mediaUrl, $serialized->media_url);
        $this->assertEquals($this->mediaUrlHttps, $serialized->media_url_https);
        $this->assertEquals($this->url, $serialized->url);
        $this->assertEquals($this->displayUrl, $serialized->display_url);
        $this->assertEquals($this->expandedUrl, $serialized->expanded_url);
        $this->assertEquals($this->type, $serialized->type);
        $this->assertEquals($this->serializedIndices, $serialized->indices);
        $this->assertEquals([$this->sizeName => $this->serializedSize], $serialized->sizes);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->mediaSizeSerializer->shouldReceive('unserialize')->andReturn($this->mediaSize);

        $mediaObj = new \stdClass();
        $mediaObj->id = $this->id;
        $mediaObj->media_url = $this->mediaUrl;
        $mediaObj->media_url_https = $this->mediaUrlHttps;
        $mediaObj->url = $this->url;
        $mediaObj->display_url = $this->displayUrl;
        $mediaObj->expanded_url = $this->expandedUrl;
        $mediaObj->type = $this->type;
        $mediaObj->indices = $this->serializedIndices;
        $mediaObj->sizes = [ $this->sizeName => $this->serializedSize ];

        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($this->indices);

        $media = $this->serviceUnderTest->unserialize($mediaObj);

        $this->assertEquals($this->id, $media->getId());
        $this->assertEquals($this->mediaUrl, $media->getMediaUrl());
        $this->assertEquals($this->mediaUrlHttps, $media->getMediaUrlHttps());
        $this->assertEquals($this->url, $media->getUrl());
        $this->assertEquals($this->displayUrl, $media->getDisplayUrl());
        $this->assertEquals($this->expandedUrl, $media->getExpandedUrl());
        $this->assertEquals($this->type, $media->getType());
        $this->assertEquals($this->indices, $media->getIndices());
        $this->assertEquals([$this->sizeName => $this->mediaSize], $media->getSizes());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterMediaSerializer::build();

        $this->assertInstanceOf(TwitterMediaSerializer::class, $serializer);
    }
}
