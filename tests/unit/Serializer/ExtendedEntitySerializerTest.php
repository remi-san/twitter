<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterMediaSize;
use Twitter\Object\TwitterVariantMedia;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterExtendedEntitySerializer;
use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\Serializer\TwitterVariantMediaSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class ExtendedEntitySerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterExtendedEntitySerializer
     */
    private $serializer;

    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    /**
     * @var TwitterMediaSizeSerializer
     */
    private $mediaSizeSerializer;

    /**
     * @var TwitterVariantMediaSerializer
     */
    private $variantMediaSerializer;

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->mediaSizeSerializer = $this->getMediaSizeSerializer();
        $this->variantMediaSerializer = $this->getVariantMediaSerializer();
        $this->serializer = new TwitterExtendedEntitySerializer(
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
        $id = 42;
        $mediaUrl = 'http://media.url';
        $mediaUrlHttps = 'https://media.url';
        $url = 'http://ur.l';
        $displayUrl = 'http://display.url';
        $expandedUrl = 'http://expanded.url';
        $type = 'type';
        $videoInfo = 'info';
        $durationMillis = 1000;

        $sizeName = 'screen';
        $mediaSize = $this->getTwitterMediaSize();
        $mediaSize->shouldReceive('getName')->andReturn($sizeName);
        $sizeObj = new \stdClass();
        $this->mediaSizeSerializer->shouldReceive('serialize')->with($mediaSize)->andReturn($sizeObj);

        $variant = $this->getVariantMedia();
        $variantObj = new \stdClass();
        $this->variantMediaSerializer->shouldReceive('serialize')->with($variant)->andReturn($variantObj);

        $indices = $this->getIndices();
        $indicesObj = new \stdClass();
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($indices)->andReturn($indicesObj);

        $obj = $this->getExtendedEntity();
        $obj->shouldReceive('getId')->andReturn($id);
        $obj->shouldReceive('getMediaUrl')->andReturn($mediaUrl);
        $obj->shouldReceive('getMediaUrlHttps')->andReturn($mediaUrlHttps);
        $obj->shouldReceive('getUrl')->andReturn($url);
        $obj->shouldReceive('getDisplayUrl')->andReturn($displayUrl);
        $obj->shouldReceive('getExpandedUrl')->andReturn($expandedUrl);
        $obj->shouldReceive('getType')->andReturn($type);
        $obj->shouldReceive('getVideoInfo')->andReturn($videoInfo);
        $obj->shouldReceive('getDurationMillis')->andReturn($durationMillis);
        $obj->shouldReceive('getIndices')->andReturn($indices);
        $obj->shouldReceive('getSizes')->andReturn(array($mediaSize));
        $obj->shouldReceive('getVariants')->andReturn(array($variant));

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($id, $serialized->id);
        $this->assertEquals($mediaUrl, $serialized->media_url);
        $this->assertEquals($mediaUrlHttps, $serialized->media_url_https);
        $this->assertEquals($url, $serialized->url);
        $this->assertEquals($displayUrl, $serialized->display_url);
        $this->assertEquals($expandedUrl, $serialized->expanded_url);
        $this->assertEquals($type, $serialized->type);
        $this->assertEquals($videoInfo, $serialized->video_info);
        $this->assertEquals($durationMillis, $serialized->duration_millis);
        $this->assertEquals($indicesObj, $serialized->indices);
        $this->assertEquals(array($sizeName => $sizeObj), $serialized->sizes);
        $this->assertEquals(array($variantObj), $serialized->variants);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $sizeName = '1080p';
        $sizeObj = new \stdClass();
        $sizeObj->w = 1920;
        $sizeObj->h = 1080;
        $sizeObj->resize = true;
        $sizeObjs = array( $sizeName => $sizeObj);

        $size = new TwitterMediaSize($sizeName, $sizeObj->w, $sizeObj->h, $sizeObj->resize);
        $this->mediaSizeSerializer->shouldReceive('unserialize')->andReturn($size);

        $variantObj = new \stdClass();
        $variantObj->content_type = 'video/mpeg';
        $variantObj->url = 'http://video.url';
        $variantObj->bitrate = 320;

        $variant = new TwitterVariantMedia($variantObj->content_type, $variantObj->url, $variantObj->bitrate);
        $this->variantMediaSerializer->shouldReceive('unserialize')->andReturn($variant);

        $indices = new TwitterEntityIndices(42, 666);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $extendedEntityObj = new \stdClass();
        $extendedEntityObj->id = 42;
        $extendedEntityObj->media_url = 'http://media.url';
        $extendedEntityObj->media_url_https = 'https://media.url';
        $extendedEntityObj->url = 'http://ur.l';
        $extendedEntityObj->display_url = 'http://display.url';
        $extendedEntityObj->expanded_url = 'http://expanded.url';
        $extendedEntityObj->type = 'type';
        $extendedEntityObj->video_info = 'info';
        $extendedEntityObj->duration_millis = 1000;

        $extendedEntityObj->indices = array(42, 666);
        $extendedEntityObj->sizes = $sizeObjs;
        $extendedEntityObj->variants = array($variantObj);

        $extendedEntity = $this->serializer->unserialize($extendedEntityObj);

        $this->assertEquals($extendedEntityObj->id, $extendedEntity->getId());
        $this->assertEquals($extendedEntityObj->media_url, $extendedEntity->getMediaUrl());
        $this->assertEquals($extendedEntityObj->media_url_https, $extendedEntity->getMediaUrlHttps());
        $this->assertEquals($extendedEntityObj->url, $extendedEntity->getUrl());
        $this->assertEquals($extendedEntityObj->display_url, $extendedEntity->getDisplayUrl());
        $this->assertEquals($extendedEntityObj->expanded_url, $extendedEntity->getExpandedUrl());
        $this->assertEquals($extendedEntityObj->type, $extendedEntity->getType());
        $this->assertEquals($extendedEntityObj->video_info, $extendedEntity->getVideoInfo());
        $this->assertEquals($extendedEntityObj->duration_millis, $extendedEntity->getDurationMillis());

        $this->assertEquals($indices, $extendedEntity->getIndices());
        $this->assertEquals(array($sizeName => $size), $extendedEntity->getSizes());
        $this->assertEquals(array($variant), $extendedEntity->getVariants());
    }
} 