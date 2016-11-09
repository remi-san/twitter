<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterMediaSize;
use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\TwitterSerializable;

class MediaSizeSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $name;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var bool */
    private $resize;

    /** @var TwitterMediaSizeSerializer */
    private $serializer;

    public function setUp()
    {
        $this->name = 'name';
        $this->width = 1920;
        $this->height = 1080;
        $this->resize = false;

        $this->serializer = new TwitterMediaSizeSerializer();
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
        $serialized = $this->serializer->serialize($this->getValidObject());

        $this->assertEquals($this->width, $serialized->w);
        $this->assertEquals($this->height, $serialized->h);
        $this->assertEquals($this->resize, $serialized->resize);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $mediaSize = $this->serializer
            ->unserialize(
                $this->getValidSerializedObject(),
                array(TwitterMediaSizeSerializer::NAME_VAR => $this->name)
            );

        $this->assertEquals($this->name, $mediaSize->getName());
        $this->assertEquals($this->resize, $mediaSize->getResize());
        $this->assertEquals($this->width, $mediaSize->getWidth());
        $this->assertEquals($this->height, $mediaSize->getHeight());
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
        $serializer = TwitterMediaSizeSerializer::build();

        $this->assertInstanceOf(TwitterMediaSizeSerializer::class, $serializer);
    }

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return TwitterMediaSize
     */
    private function getValidObject()
    {
        return TwitterMediaSize::create($this->name, $this->width, $this->height, $this->resize);
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        $mediaSizeObj = new \stdClass();
        $mediaSizeObj->w = $this->width;
        $mediaSizeObj->h = $this->height;
        $mediaSizeObj->resize = $this->resize;
        return $mediaSizeObj;
    }

    /**
     * @return \stdClass
     */
    private function getInvalidSerializedObject()
    {
        return new \stdClass();
    }
}
