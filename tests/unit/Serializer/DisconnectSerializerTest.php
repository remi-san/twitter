<?php
namespace Twitter\Test\Serializer;

use Faker\Factory;
use Mockery\Mock;
use Twitter\Object\TwitterDisconnect;
use Twitter\Serializer\TwitterDisconnectSerializer;
use Twitter\TwitterSerializable;

class DisconnectSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $code;

    /** @var string */
    private $streamName;

    /** @var string */
    private $reason;

    /** @var TwitterDisconnect | Mock */
    private $twitterDisconnect;

    /** @var object */
    private $serializedTwitterDisconnect;

    /** @var TwitterDisconnectSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $faker = Factory::create();

        $this->code = (string) $faker->randomNumber();
        $this->streamName = $faker->word;
        $this->reason = $faker->text();

        $this->twitterDisconnect = TwitterDisconnect::create(
            $this->code,
            $this->streamName,
            $this->reason
        );
        $this->serializedTwitterDisconnect = $this->getSerializedTwitterDisconnect();

        $this->serviceUnderTest = new TwitterDisconnectSerializer();
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

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $serialized = $this->serviceUnderTest->serialize($this->twitterDisconnect);

        $this->assertEquals($this->code, $serialized->disconnect->code);
        $this->assertEquals($this->streamName, $serialized->disconnect->stream_name);
        $this->assertEquals($this->reason, $serialized->disconnect->reason);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $innerDisconnect = $this->serializedTwitterDisconnect->disconnect;

        $disconnect = $this->serviceUnderTest->unserialize($this->serializedTwitterDisconnect);

        $this->assertEquals($innerDisconnect->code, $disconnect->getCode());
        $this->assertEquals($innerDisconnect->stream_name, $disconnect->getStreamName());
        $this->assertEquals($innerDisconnect->reason, $disconnect->getReason());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = $this->getIllegalSerializedObject();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterDisconnectSerializer::build();

        $this->assertInstanceOf(TwitterDisconnectSerializer::class, $serializer);
    }

    /**
     * @return object
     */
    private function getSerializedTwitterDisconnect()
    {
        $innerDisconnect = new \stdClass();
        $innerDisconnect->code = $this->code;
        $innerDisconnect->stream_name = $this->streamName;
        $innerDisconnect->reason = $this->reason;

        $serializedTwitterDisconnect = new \stdClass();
        $serializedTwitterDisconnect->disconnect = $innerDisconnect;

        return $serializedTwitterDisconnect;
    }

    /**
     * @return \stdClass
     */
    private function getIllegalSerializedObject()
    {
        return new \stdClass();
    }
}
