<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterPlace;
use Twitter\Serializer\TwitterPlaceSerializer;
use Twitter\TwitterSerializable;

class PlaceSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TwitterPlaceSerializer */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterPlaceSerializer();
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

        $this->assertEquals(new \stdClass(), $serialized);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $place = $this->serializer->unserialize($this->getValidSerializedObject());

        $this->assertTrue($place instanceof TwitterPlace);
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
        $serializer = TwitterPlaceSerializer::build();

        $this->assertInstanceOf(TwitterPlaceSerializer::class, $serializer);
    }

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return TwitterPlace
     */
    private function getValidObject()
    {
        return \Mockery::mock(TwitterPlace::class);
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        return new \stdClass();
    }

    /**
     * @return null
     */
    private function getInvalidSerializedObject()
    {
        return null;
    }
}
