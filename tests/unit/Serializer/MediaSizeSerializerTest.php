<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class MediaSizeSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterMediaSizeSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterMediaSizeSerializer();
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
        $obj = $this->getTwitterMediaSize();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $sizeName = '1080p';

        $mediaSizeObj = new \stdClass();
        $mediaSizeObj->w = 1920;
        $mediaSizeObj->h = 1080;
        $mediaSizeObj->resize = false;

        $mediaSize = $this->serializer->unserialize($mediaSizeObj, array(TwitterMediaSizeSerializer::NAME_VAR => $sizeName));

        $this->assertEquals($sizeName, $mediaSize->getName());
        $this->assertEquals($mediaSizeObj->resize, $mediaSize->getResize());
        $this->assertEquals($mediaSizeObj->w, $mediaSize->getWidth());
        $this->assertEquals($mediaSizeObj->h, $mediaSize->getHeight());
    }
} 