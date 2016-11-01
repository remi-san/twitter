<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterSerializable;

class EntityIndicesSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /** @var TwitterEntityIndicesSerializer */
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
    public function itShouldUnserialize()
    {
        $indicesObj = array(42, 666);

        $indices = $this->serializer->unserialize($indicesObj);

        $this->assertEquals(42, $indices->getFrom());
        $this->assertEquals(666, $indices->getTo());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterEntityIndicesSerializer::build();

        $this->assertInstanceOf(TwitterEntityIndicesSerializer::class, $serializer);
    }
}
