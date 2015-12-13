<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterCoordinatesSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class CoordinatesSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterCoordinatesSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterCoordinatesSerializer();
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
        $long = 24;
        $lat = 42;
        $type = 'point';

        $obj = $this->getCoordinates();
        $obj->shouldReceive('getLongitude')->andReturn($long);
        $obj->shouldReceive('getLatitude')->andReturn($lat);
        $obj->shouldReceive('getType')->andReturn($type);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($long, $serialized->coordinates[0]);
        $this->assertEquals($lat, $serialized->coordinates[1]);
        $this->assertEquals($type, $serialized->type);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $obj = new \stdClass();
        $obj->coordinates = array(24, 42);
        $obj->type = 'point';

        $coordinates = $this->serializer->unserialize($obj);

        $this->assertEquals($obj->coordinates[0], $coordinates->getLongitude());
        $this->assertEquals($obj->coordinates[1], $coordinates->getLatitude());
        $this->assertEquals($obj->type, $coordinates->getType());
    }

    /**
     * @test
     */
    public function testStaticBuilder()
    {
        $serializer = TwitterCoordinatesSerializer::build();

        $this->assertInstanceOf(TwitterCoordinatesSerializer::class, $serializer);
    }
}
