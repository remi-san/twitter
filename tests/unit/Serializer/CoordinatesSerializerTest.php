<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterCoordinatesSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class CoordinatesSerializerTest extends \PHPUnit_Framework_TestCase {
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
        $obj = $this->getCoordinates();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
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
} 