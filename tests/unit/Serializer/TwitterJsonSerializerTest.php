<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterDeleteSerializer;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterDisconnectSerializer;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Serializer\TwitterFriendsSerializer;
use Twitter\Serializer\TwitterJsonSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class TwitterJsonSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterJsonSerializer
     */
    private $serializer;

    /**
     * @var TwitterEventTargetSerializer
     */
    private $eventTargetSerializer;

    /**
     * @var TwitterDirectMessageSerializer
     */
    private $directMessageSerializer;

    /**
     * @var TwitterEventSerializer
     */
    private $eventSerializer;

    /**
     * @var TwitterFriendsSerializer
     */
    private $friendsSerializer;

    /**
     * @var TwitterDisconnectSerializer
     */
    private $disconnectSerializer;

    /**
     * @var TwitterDeleteSerializer
     */
    private $deleteSerializer;

    public function setUp()
    {
        $this->eventTargetSerializer = $this->getEventTargetSerializer();
        $this->directMessageSerializer = $this->getDirectMessageSerializer();
        $this->eventSerializer = $this->getEventSerializer();
        $this->friendsSerializer = $this->getFriendsSerializer();
        $this->disconnectSerializer = $this->getDisconnectSerializer();
        $this->deleteSerializer = $this->getDeleteSerializer();

        $this->serializer = new TwitterJsonSerializer(
            $this->eventTargetSerializer,
            $this->directMessageSerializer,
            $this->eventSerializer,
            $this->friendsSerializer,
            $this->disconnectSerializer,
            $this->deleteSerializer
        );
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testSerializeWithLegalObject()
    {
        $obj = $this->getUserMention('user');

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserializeTweet()
    {
        $obj = new \stdClass();
        $obj->text = 'text';
        $obj->user = new \stdClass();

        $tweet = $this->getTweet();

        $this->eventTargetSerializer->shouldReceive('unserialize')->andReturn($tweet)->once();

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($tweet, $return);
    }

    /**
     * @test
     */
    public function testUnserializeDM()
    {
        $obj = new \stdClass();
        $obj->direct_message = new \stdClass();

        $dm = $this->getDirectMessage();

        $this->directMessageSerializer->shouldReceive('unserialize')->andReturn($dm)->once();

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($dm, $return);
    }

    /**
     * @test
     */
    public function testUnserializeEvent()
    {
        $obj = new \stdClass();
        $obj->event = 'dm';

        $event = $this->getEvent();

        $this->eventSerializer->shouldReceive('unserialize')->andReturn($event)->once();

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($event, $return);
    }

    /**
     * @test
     */
    public function testUnserializeFriends()
    {
        $obj = new \stdClass();
        $obj->friends = [];

        $friends = $this->getFriends();

        $this->friendsSerializer->shouldReceive('unserialize')->andReturn($friends)->once();

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($friends, $return);
    }

    /**
     * @test
     */
    public function testUnserializeDisconnect()
    {
        $obj = new \stdClass();
        $obj->disconnect = new \stdClass();

        $disconnect = $this->getDisconnect();

        $this->disconnectSerializer->shouldReceive('unserialize')->andReturn($disconnect)->once();

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($disconnect, $return);
    }

    /**
     * @test
     */
    public function testUnserializeDelete()
    {
        $obj = new \stdClass();
        $obj->delete = new \stdClass();

        $delete = $this->getDeleteSerializer();

        $this->deleteSerializer->shouldReceive('unserialize')->andReturn($delete)->once();

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($delete, $return);
    }
} 