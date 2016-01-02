<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\Tweet;
use Twitter\Object\TwitterEvent;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterMessageId;

class EventSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterEventSerializer
     */
    private $serializer;

    /**
     * @var TwitterUserSerializer
     */
    private $userSerializer;

    /**
     * @var TwitterEventTargetSerializer
     */
    private $eventTargetSerializer;

    public function setUp()
    {
        $this->userSerializer = $this->getUserSerializer();
        $this->eventTargetSerializer = $this->getEventTargetSerializer();
        $this->serializer = new TwitterEventSerializer($this->userSerializer, $this->eventTargetSerializer);
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
        $type = TwitterEvent::FAVORITE;
        $date = new \DateTime();

        $sourceObj = new \stdClass();
        $sourceObj->type = 'source';
        $source = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('serialize')->with($source)->andReturn($sourceObj);

        $userObj = new \stdClass();
        $userObj->type = 'user';
        $user = $this->getTwitterUser(42, 'douglas');
        $this->userSerializer->shouldReceive('serialize')->with($user)->andReturn($userObj);

        $tweetObj = new \stdClass();
        $tweetObj->type = 'tweet';
        $tweet = new Tweet(new TwitterMessageId(1), new TwitterUser(), 'text', 'fr', new \DateTime());
        $this->eventTargetSerializer->shouldReceive('serialize')->with($tweet)->andReturn($tweetObj);

        $obj = $this->getEvent();
        $obj->shouldReceive('getType')->andReturn($type);
        $obj->shouldReceive('getSource')->andReturn($source);
        $obj->shouldReceive('getTarget')->andReturn($user);
        $obj->shouldReceive('getObject')->andReturn($tweet);
        $obj->shouldReceive('getDate')->andReturn($date);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($type, $serialized->event);
        $this->assertEquals($date, new \DateTime($serialized->created_at));
        $this->assertEquals($sourceObj, $serialized->source);
        $this->assertEquals($userObj, $serialized->target);
        $this->assertEquals($tweetObj, $serialized->target_object);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $sourceObj = new \stdClass();
        $sourceObj->type = 'source';
        $source = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('unserialize')->with($sourceObj)->andReturn($source);

        $userObj = new \stdClass();
        $userObj->type = 'user';
        $user = $this->getTwitterUser(42, 'douglas');
        $this->userSerializer->shouldReceive('unserialize')->with($userObj)->andReturn($user);

        $tweetObj = new \stdClass();
        $tweetObj->type = 'tweet';
        $tweet = new Tweet(new TwitterMessageId(1), new TwitterUser(), 'text', 'fr', new \DateTime());
        $this->eventTargetSerializer->shouldReceive('unserialize')->with($tweetObj)->andReturn($tweet);

        $eventObj = new \stdClass();
        $eventObj->event = '';
        $eventObj->source = $sourceObj;
        $eventObj->target = $userObj;
        $eventObj->target_object = $tweetObj;
        $eventObj->created_at = '2015-01-01 12:00:00';

        $event = $this->serializer->unserialize($eventObj);

        $this->assertEquals($eventObj->event, $event->getType());
        $this->assertEquals($source, $event->getSource());
        $this->assertEquals($user, $event->getTarget());
        $this->assertEquals($tweet, $event->getObject());
        $this->assertEquals(new \DateTime($eventObj->created_at), $event->getDate());
    }

    /**
     * @test
     */
    public function testStaticBuilder()
    {
        $serializer = TwitterEventSerializer::build();

        $this->assertInstanceOf(TwitterEventSerializer::class, $serializer);
    }
}
