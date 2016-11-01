<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterPlace;
use Twitter\Serializer\TwitterPlaceSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterSerializable;

class PlaceSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

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
        $object = \Mockery::mock(TwitterSerializable::class);

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $obj = $this->getPlace();

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(new \stdClass(), $serialized);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $placeObj = new \stdClass();

        $place = $this->serializer->unserialize($placeObj);

        $this->assertTrue($place instanceof TwitterPlace);
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize(null);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterPlaceSerializer::build();

        $this->assertInstanceOf(TwitterPlaceSerializer::class, $serializer);
    }
}
