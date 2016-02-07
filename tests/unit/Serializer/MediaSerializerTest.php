<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterMediaSize;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterMediaSerializer;
use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class MediaSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterMediaSerializer
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

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->mediaSizeSerializer = $this->getMediaSizeSerializer();
        $this->serializer = new TwitterMediaSerializer(
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

        $sizeName = 'screen';
        $mediaSize = $this->getTwitterMediaSize();
        $mediaSize->shouldReceive('getName')->andReturn($sizeName);
        $sizeObj = new \stdClass();
        $this->mediaSizeSerializer->shouldReceive('serialize')->with($mediaSize)->andReturn($sizeObj);

        $indices = $this->getIndices();
        $indicesObj = new \stdClass();
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($indices)->andReturn($indicesObj);

        $obj = $this->getMedia();
        $obj->shouldReceive('getId')->andReturn($id);
        $obj->shouldReceive('getMediaUrl')->andReturn($mediaUrl);
        $obj->shouldReceive('getMediaUrlHttps')->andReturn($mediaUrlHttps);
        $obj->shouldReceive('getUrl')->andReturn($url);
        $obj->shouldReceive('getDisplayUrl')->andReturn($displayUrl);
        $obj->shouldReceive('getExpandedUrl')->andReturn($expandedUrl);
        $obj->shouldReceive('getType')->andReturn($type);
        $obj->shouldReceive('getIndices')->andReturn($indices);
        $obj->shouldReceive('getSizes')->andReturn(array($mediaSize));

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($id, $serialized->id);
        $this->assertEquals($mediaUrl, $serialized->media_url);
        $this->assertEquals($mediaUrlHttps, $serialized->media_url_https);
        $this->assertEquals($url, $serialized->url);
        $this->assertEquals($displayUrl, $serialized->display_url);
        $this->assertEquals($expandedUrl, $serialized->expanded_url);
        $this->assertEquals($type, $serialized->type);
        $this->assertEquals($indicesObj, $serialized->indices);
        $this->assertEquals(array($sizeName => $sizeObj), $serialized->sizes);
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

        $size = TwitterMediaSize::create($sizeName, $sizeObj->w, $sizeObj->h, $sizeObj->resize);
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

        $indices = TwitterEntityIndices::create(42, 666);
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

    /**
     * @test
     */
    public function testStaticBuilder()
    {
        $serializer = TwitterMediaSerializer::build();

        $this->assertInstanceOf(TwitterMediaSerializer::class, $serializer);
    }
}
