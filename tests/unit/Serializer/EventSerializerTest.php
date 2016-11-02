<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterDate;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterEvent;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\TwitterMessageId;
use Twitter\TwitterSerializable;

class EventSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TwitterUserSerializer | Mock */
    private $userSerializer;

    /** @var TwitterEventTargetSerializer | Mock */
    private $eventTargetSerializer;

    /** @var TwitterEventSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $this->userSerializer = \Mockery::mock(TwitterUserSerializer::class);
        $this->eventTargetSerializer = \Mockery::mock(TwitterEventTargetSerializer::class);

        $this->serviceUnderTest = new TwitterEventSerializer($this->userSerializer, $this->eventTargetSerializer);
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
        $type = TwitterEvent::FAVORITE;
        $date = new \DateTimeImmutable();

        $sourceObj = new \stdClass();
        $source = \Mockery::mock(TwitterUser::class);
        $this->userSerializer->shouldReceive('serialize')->with($source)->andReturn($sourceObj);

        $userObj = new \stdClass();
        $user = \Mockery::mock(TwitterUser::class);
        $this->userSerializer->shouldReceive('serialize')->with($user)->andReturn($userObj);

        $tweetObj = new \stdClass();
        $tweet = Tweet::create(
            TwitterMessageId::create(1),
            TwitterUser::create(),
            'text',
            'fr',
            new \DateTimeImmutable(),
            \Mockery::mock(TwitterEntities::class)
        );
        $this->eventTargetSerializer->shouldReceive('serialize')->with($tweet)->andReturn($tweetObj);

        $obj = TwitterEvent::create(
            $type,
            $source,
            $user,
            $tweet,
            $date
        );

        $serialized = $this->serviceUnderTest->serialize($obj);

        $this->assertEquals($type, $serialized->event);
        $this->assertEquals($date->getTimestamp(), (new \DateTimeImmutable($serialized->created_at))->getTimestamp());
        $this->assertEquals($sourceObj, $serialized->source);
        $this->assertEquals($userObj, $serialized->target);
        $this->assertEquals($tweetObj, $serialized->target_object);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $sourceObj = new \stdClass();
        $source = \Mockery::mock(TwitterUser::class);
        $this->userSerializer->shouldReceive('unserialize')->with($sourceObj)->andReturn($source);

        $userObj = new \stdClass();
        $user = \Mockery::mock(TwitterUser::class);
        $this->userSerializer->shouldReceive('unserialize')->with($userObj)->andReturn($user);

        $tweetObj = new \stdClass();
        $tweet = Tweet::create(
            TwitterMessageId::create(1),
            TwitterUser::create(),
            'text',
            'fr',
            new \DateTimeImmutable(),
            \Mockery::mock(TwitterEntities::class)
        );
        $this->eventTargetSerializer->shouldReceive('unserialize')->with($tweetObj)->andReturn($tweet);

        $eventObj = new \stdClass();
        $eventObj->event = '';
        $eventObj->source = $sourceObj;
        $eventObj->target = $userObj;
        $eventObj->target_object = $tweetObj;
        $eventObj->created_at = (new \DateTimeImmutable('2015-01-01', new \DateTimeZone('UTC')))
            ->format(TwitterDate::FORMAT);

        $event = $this->serviceUnderTest->unserialize($eventObj);

        $this->assertEquals($eventObj->event, $event->getType());
        $this->assertEquals($source, $event->getSource());
        $this->assertEquals($user, $event->getTarget());
        $this->assertEquals($tweet, $event->getObject());
        $this->assertEquals(new \DateTimeImmutable($eventObj->created_at), $event->getDate());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterEventSerializer::build();

        $this->assertInstanceOf(TwitterEventSerializer::class, $serializer);
    }
}
