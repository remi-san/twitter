<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterDisconnect;

class DisconnectTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $code;

    /** @var string */
    private $streamName;

    /** @var string */
    private $reason;

    public function setUp()
    {
        $faker = Factory::create();

        $this->code = $faker->word;
        $this->streamName = $faker->word;
        $this->reason = $faker->text();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $disconnect = TwitterDisconnect::create($this->code, $this->streamName, $this->reason);

        $this->assertEquals($this->code, $disconnect->getCode());
        $this->assertEquals($this->streamName, $disconnect->getStreamName());
        $this->assertEquals($this->reason, $disconnect->getReason());
        $this->assertEquals('Disconnect ['.$this->streamName.']', (string) $disconnect);
    }
}
