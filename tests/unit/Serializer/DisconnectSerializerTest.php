<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterDisconnectSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterSerializable;

class DisconnectSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /** @var TwitterDisconnectSerializer */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterDisconnectSerializer();
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
        $code = '42';
        $streamName = 'abcde';
        $reason = 'whatever';

        $obj = $this->getDisconnect();
        $obj->shouldReceive('getCode')->andReturn($code);
        $obj->shouldReceive('getStreamName')->andReturn($streamName);
        $obj->shouldReceive('getReason')->andReturn($reason);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($code, $serialized->disconnect->code);
        $this->assertEquals($streamName, $serialized->disconnect->stream_name);
        $this->assertEquals($reason, $serialized->disconnect->reason);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $disconnectObj = new \stdClass();
        $disconnectObj->code = 'code';
        $disconnectObj->stream_name = 'stream';
        $disconnectObj->reason = 'reason';

        $d = new \stdClass();
        $d->disconnect = $disconnectObj;

        $disconnect = $this->serializer->unserialize($d);

        $this->assertEquals($disconnectObj->code, $disconnect->getCode());
        $this->assertEquals($disconnectObj->stream_name, $disconnect->getStreamName());
        $this->assertEquals($disconnectObj->reason, $disconnect->getReason());
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
        $serializer = TwitterDisconnectSerializer::build();

        $this->assertInstanceOf(TwitterDisconnectSerializer::class, $serializer);
    }
}
