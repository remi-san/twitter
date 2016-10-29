<?php
namespace Twitter\Test\Serializer;

use Faker\Factory;
use Twitter\Object\TwitterCoordinates;
use Twitter\Serializer\TwitterCoordinatesSerializer;
use Twitter\TwitterSerializable;

class CoordinatesSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var float */
    private $longitude;

    /** @var float */
    private $latitude;

    /** @var string */
    private $type;

    /** @var TwitterCoordinates */
    private $coordinates;

    /** @var TwitterCoordinatesSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $faker = Factory::create();

        $this->longitude = $faker->longitude;
        $this->latitude = $faker->latitude;
        $this->type = TwitterCoordinates::TYPE_POINT;

        $this->coordinates = TwitterCoordinates::create($this->longitude, $this->latitude, $this->type);

        $this->serviceUnderTest = new TwitterCoordinatesSerializer();
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

        $this->serviceUnderTest->serialize(\Mockery::mock(TwitterSerializable::class));
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $serialized = $this->serviceUnderTest->serialize($this->coordinates);

        $this->assertEquals($this->longitude, $serialized->coordinates[0]);
        $this->assertEquals($this->latitude, $serialized->coordinates[1]);
        $this->assertEquals($this->type, $serialized->type);
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($this->buildIllegalSerializedObject());
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $coordinates = $this->serviceUnderTest->unserialize($this->buildSerializedCoordinates());

        $this->assertEquals($this->longitude, $coordinates->getLongitude());
        $this->assertEquals($this->latitude, $coordinates->getLatitude());
        $this->assertEquals($this->type, $coordinates->getType());
    }

    /**
     * @test
     */
    public function itShouldBuildSerializerThroughStaticBuilder()
    {
        $serializer = TwitterCoordinatesSerializer::build();

        $this->assertInstanceOf(TwitterCoordinatesSerializer::class, $serializer);
    }

    /**
     * @return \stdClass
     */
    private function buildSerializedCoordinates()
    {
        $serializedObject = new \stdClass();
        $serializedObject->coordinates = array($this->longitude, $this->latitude);
        $serializedObject->type = $this->type;

        return $serializedObject;
    }

    /**
     * @return \stdClass
     */
    private function buildIllegalSerializedObject()
    {
        return new \stdClass();
    }
}
