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
        $from = 42;
        $to = 666;

        $obj = $this->getTwitterEntityIndices();
        $obj->shouldReceive('getFrom')->andReturn($from);
        $obj->shouldReceive('getTo')->andReturn($to);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($from, $serialized[0]);
        $this->assertEquals($to, $serialized[1]);
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