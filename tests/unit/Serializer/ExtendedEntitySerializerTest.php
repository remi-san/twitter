<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterExtendedEntity;
use Twitter\Object\TwitterMediaSize;
use Twitter\Object\TwitterVariantMedia;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterExtendedEntitySerializer;
use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\Serializer\TwitterVariantMediaSerializer;
use Twitter\TwitterSerializable;

class ExtendedEntitySerializerTest extends \PHPUnit_Framework_TestCase
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
    private $videoInfo;

    /** @var int */
    private $durationMillis;

    /** @var string */
    private $sizeName;


    /** @var TwitterMediaSize | Mock */
    private $mediaSize;

    /** @var TwitterVariantMedia | Mock */
    private $variant;

    /** @var TwitterEntityIndices | Mock */
    private $indices;


    /** @var object */
    private $serializedSize;

    /** @var object */
    private $serializedVariant;

    /** @var int[] */
    private $serializedIndices;


    /** @var TwitterEntityIndicesSerializer | Mock */
    private $entityIndicesSerializer;

    /** @var TwitterMediaSizeSerializer | Mock */
    private $mediaSizeSerializer;

    /** @var TwitterVariantMediaSerializer | Mock */
    private $variantMediaSerializer;

    /** @var TwitterExtendedEntitySerializer */
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
        $this->videoInfo = 'info';
        $this->durationMillis = 1000;
        $this->sizeName = 'screen';

        $this->mediaSize = \Mockery::mock(TwitterMediaSize::class);
        $this->variant = \Mockery::mock(TwitterVariantMedia::class);
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);

        $this->serializedSize = new \stdClass();
        $this->serializedVariant = new \stdClass();
        $this->serializedIndices = [];

        $this->entityIndicesSerializer = \Mockery::mock(TwitterEntityIndicesSerializer::class);
        $this->mediaSizeSerializer = \Mockery::mock(TwitterMediaSizeSerializer::class);
        $this->variantMediaSerializer = \Mockery::mock(TwitterVariantMediaSerializer::class);

        $this->serviceUnderTest = new TwitterExtendedEntitySerializer(
            $this->entityIndicesSerializer,
            $this->mediaSizeSerializer,
            $this->variantMediaSerializer
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
        $object = $this->getIllegalObject();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $this->itWillSerializeMediaSize();
        $this->itWillSerializeVariantMedia();
        $this->itWillSerializeIndices();

        $serialized = $this->serviceUnderTest->serialize($this->getTwitterExtendedEntity());

        $this->assertEquals($this->id, $serialized->id);
        $this->assertEquals($this->mediaUrl, $serialized->media_url);
        $this->assertEquals($this->mediaUrlHttps, $serialized->media_url_https);
        $this->assertEquals($this->url, $serialized->url);
        $this->assertEquals($this->displayUrl, $serialized->display_url);
        $this->assertEquals($this->expandedUrl, $serialized->expanded_url);
        $this->assertEquals($this->type, $serialized->type);
        $this->assertEquals($this->videoInfo, $serialized->video_info);
        $this->assertEquals($this->durationMillis, $serialized->duration_millis);
        $this->assertEquals($this->serializedIndices, $serialized->indices);
        $this->assertEquals([$this->sizeName => $this->serializedSize], $serialized->sizes);
        $this->assertEquals([$this->serializedVariant], $serialized->variants);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->itWillUnserializeMediaSize();
        $this->itWillUnserializeVariantMedia();
        $this->itWillUnserializeIndices();

        $extendedEntity = $this->serviceUnderTest->unserialize($this->getSerializedObject());

        $this->assertEquals($this->id, $extendedEntity->getId());
        $this->assertEquals($this->mediaUrl, $extendedEntity->getMediaUrl());
        $this->assertEquals($this->mediaUrlHttps, $extendedEntity->getMediaUrlHttps());
        $this->assertEquals($this->url, $extendedEntity->getUrl());
        $this->assertEquals($this->displayUrl, $extendedEntity->getDisplayUrl());
        $this->assertEquals($this->expandedUrl, $extendedEntity->getExpandedUrl());
        $this->assertEquals($this->type, $extendedEntity->getType());
        $this->assertEquals($this->videoInfo, $extendedEntity->getVideoInfo());
        $this->assertEquals($this->durationMillis, $extendedEntity->getDurationMillis());
        $this->assertEquals($this->indices, $extendedEntity->getIndices());
        $this->assertEquals([$this->sizeName => $this->mediaSize], $extendedEntity->getSizes());
        $this->assertEquals([$this->variant], $extendedEntity->getVariants());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = $this->getIllegalSerializedObject();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterExtendedEntitySerializer::build();

        $this->assertInstanceOf(TwitterExtendedEntitySerializer::class, $serializer);
    }

    /**
     * @return TwitterExtendedEntity
     */
    private function getTwitterExtendedEntity()
    {
        return TwitterExtendedEntity::create(
            $this->id,
            $this->mediaUrl,
            $this->mediaUrlHttps,
            $this->url,
            $this->displayUrl,
            $this->expandedUrl,
            [$this->mediaSize],
            $this->type,
            $this->videoInfo,
            $this->durationMillis,
            [$this->variant],
            $this->indices
        );
    }

    /**
     * @return \stdClass
     */
    private function getSerializedObject()
    {
        $extendedEntityObj = new \stdClass();
        $extendedEntityObj->id = $this->id;
        $extendedEntityObj->media_url = $this->mediaUrl;
        $extendedEntityObj->media_url_https = $this->mediaUrlHttps;
        $extendedEntityObj->url = $this->url;
        $extendedEntityObj->display_url = $this->displayUrl;
        $extendedEntityObj->expanded_url = $this->expandedUrl;
        $extendedEntityObj->type = $this->type;
        $extendedEntityObj->video_info = $this->videoInfo;
        $extendedEntityObj->duration_millis = $this->durationMillis;
        $extendedEntityObj->indices = [];
        $extendedEntityObj->sizes = [$this->sizeName => $this->serializedSize];
        $extendedEntityObj->variants = [$this->serializedVariant];
        return $extendedEntityObj;
    }

    private function itWillSerializeMediaSize()
    {
        $this->mediaSize->shouldReceive('getName')->andReturn($this->sizeName);
        $this->mediaSizeSerializer->shouldReceive('serialize')->with($this->mediaSize)->andReturn($this->serializedSize);
    }

    private function itWillSerializeVariantMedia()
    {
        $this->variantMediaSerializer->shouldReceive('serialize')->with($this->variant)->andReturn($this->serializedVariant);
    }

    private function itWillSerializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($this->indices)->andReturn($this->serializedIndices);
    }

    private function itWillUnserializeMediaSize()
    {
        $this->mediaSizeSerializer->shouldReceive('unserialize')->andReturn($this->mediaSize);
    }

    private function itWillUnserializeVariantMedia()
    {
        $this->variantMediaSerializer->shouldReceive('unserialize')->andReturn($this->variant);
    }

    private function itWillUnserializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($this->indices);
    }

    /**
     * @return TwitterSerializable
     */
    private function getIllegalObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return \stdClass
     */
    private function getIllegalSerializedObject()
    {
        return new \stdClass();
    }
}
