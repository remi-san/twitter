<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterMediaSize;
use Twitter\Object\TwitterVariantMedia;
use Twitter\Serializer\TwitterExtendedEntitySerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class ExtendedEntitySerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterExtendedEntitySerializer
     */
    private $serializer;

    /**
     * @var \Twitter\Serializer\TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    /**
     * @var \Twitter\Serializer\TwitterMediaSizeSerializer
     */
    private $mediaSizeSerializer;

    /**
     * @var \Twitter\Serializer\TwitterVariantMediaSerializer
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
        $obj = $this->getExtendedEntity();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
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