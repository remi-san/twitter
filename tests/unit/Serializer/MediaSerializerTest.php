<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterMediaSize;
use Twitter\Serializer\TwitterMediaSerializer;
use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class MediaSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterMediaSerializer
     */
    private $serializer;

    /**
     * @var \Twitter\Serializer\TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    /**
     * @var TwitterMediaSizeSerializer
     */
    private $mediaSizeSerializer;

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->mediaSizeSerializer = $this->getMediaSizeSerializer();
        $this->serializer = new TwitterMediaSerializer(
            $this->entityIndicesSerializer,
            $this->mediaSizeSerializer
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
        $obj = $this->getMedia();

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

        $mediaObj = new \stdClass();
        $mediaObj->id = 42;
        $mediaObj->media_url = 'http://media.url';
        $mediaObj->media_url_https = 'https://media.url';
        $mediaObj->url = 'http://ur.l';
        $mediaObj->display_url = 'http://display.url';
        $mediaObj->expanded_url = 'http://expanded.url';
        $mediaObj->type = 'type';

        $mediaObj->indices = array(42, 666);
        $mediaObj->sizes = $sizeObjs;

        $indices = new TwitterEntityIndices(42, 666);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $media = $this->serializer->unserialize($mediaObj);

        $this->assertEquals($mediaObj->id, $media->getId());
        $this->assertEquals($mediaObj->media_url, $media->getMediaUrl());
        $this->assertEquals($mediaObj->media_url_https, $media->getMediaUrlHttps());
        $this->assertEquals($mediaObj->url, $media->getUrl());
        $this->assertEquals($mediaObj->display_url, $media->getDisplayUrl());
        $this->assertEquals($mediaObj->expanded_url, $media->getExpandedUrl());
        $this->assertEquals($mediaObj->type, $media->getType());

        $this->assertEquals($indices, $media->getIndices());
        $this->assertEquals(array($sizeName => $size), $media->getSizes());
    }
} 