<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterPlace;
use Twitter\Serializer\TwitterPlaceSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class PlaceSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterPlaceSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterPlaceSerializer();
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
        $obj = $this->getPlace();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $placeObj = new \stdClass();

        $place = $this->serializer->unserialize($placeObj);

        $this->assertTrue($place instanceof TwitterPlace);
    }
} 