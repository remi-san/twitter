<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class EntityIndicesSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterEntityIndicesSerializer();
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
        $obj = $this->getTwitterEntityIndices();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $indicesObj = array(42, 666);

        $indices = $this->serializer->unserialize($indicesObj);

        $this->assertEquals(42, $indices->getFrom());
        $this->assertEquals(666, $indices->getTo());
    }
} 