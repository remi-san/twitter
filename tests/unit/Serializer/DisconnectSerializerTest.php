<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterDisconnectSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class DisconnectSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterDisconnectSerializer
     */
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
        $obj = $this->getDisconnect();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $disconnectObj = new \stdClass();
        $disconnectObj->code = 'code';
        $disconnectObj->stream_name = 'stream';
        $disconnectObj->reason = 'reason';

        $d = new \stdClass(); $d->disconnect = $disconnectObj;

        $disconnect = $this->serializer->unserialize($d);

        $this->assertEquals($disconnectObj->code, $disconnect->getCode());
        $this->assertEquals($disconnectObj->stream_name, $disconnect->getStreamName());
        $this->assertEquals($disconnectObj->reason, $disconnect->getReason());
    }
} 