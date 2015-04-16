<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\Tweet;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class EventSerializerTest extends \PHPUnit_Framework_TestCase {
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
        $obj = $this->getEvent();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $sourceObj = new \stdClass(); $sourceObj->type = 'source';
        $source = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('unserialize')->with($sourceObj)->andReturn($source);

        $userObj = new \stdClass(); $userObj->type = 'user';
        $user = $this->getTwitterUser(42, 'douglas');
        $this->userSerializer->shouldReceive('unserialize')->with($userObj)->andReturn($user);

        $tweetObj = new \stdClass(); $tweetObj->type = 'tweet';
        $tweet = new Tweet(1, new TwitterUser(), 'text', 'fr', new \DateTime());
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
} 