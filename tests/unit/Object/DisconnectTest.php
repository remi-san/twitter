<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterDisconnect;

class DisconnectTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $code = 'code';
        $streamName = 'stream';
        $reason = 'reason';

        $disconnect = TwitterDisconnect::create($code, $streamName, $reason);

        $this->assertEquals($code, $disconnect->getCode());
        $this->assertEquals($streamName, $disconnect->getStreamName());
        $this->assertEquals($reason, $disconnect->getReason());
        $this->assertEquals('Disconnect ['.$streamName.']', $disconnect->__toString());
    }
}
