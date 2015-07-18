<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterDeleteSerializer;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterDisconnectSerializer;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Serializer\TwitterFriendsSerializer;
use Twitter\Serializer\TwitterJsonSerializer;
use Twitter\Serializer\TwitterUserSerializer;
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

    /**
     * @var TwitterUserSerializer
     */
    private $userSerializer;

    public function setUp()
    {
        $this->eventTargetSerializer = $this->getEventTargetSerializer();
        $this->directMessageSerializer = $this->getDirectMessageSerializer();
        $this->eventSerializer = $this->getEventSerializer();
        $this->friendsSerializer = $this->getFriendsSerializer();
        $this->disconnectSerializer = $this->getDisconnectSerializer();
        $this->deleteSerializer = $this->getDeleteSerializer();
        $this->userSerializer = $this->getUserSerializer();

        $this->serializer = new TwitterJsonSerializer(
            $this->eventTargetSerializer,
            $this->directMessageSerializer,
            $this->eventSerializer,
            $this->friendsSerializer,
            $this->disconnectSerializer,
            $this->deleteSerializer,
            $this->userSerializer
        );
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
        $obj = new \stdClass();

        $this->setExpectedException('\\InvalidArgumentException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testSerializeWithUnsupportedObject()
    {
        $obj = $this->getUserMention('user');

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testSerializeWithUser()
    {
        $obj = $this->getTwitterUser(1, 'douglas');

        $this->userSerializer->shouldReceive('serialize')->with($obj)->andReturn(new \stdClass());

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals('{}', $serialized);
    }

    /**
     * @test
     */
    public function testSerializeWithTweet()
    {
        $obj = $this->getTweet();

        $this->eventTargetSerializer->shouldReceive('serialize')->with($obj)->andReturn(new \stdClass());

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals('{}', $serialized);
    }

    /**
     * @test
     */
    public function testSerializeWithDirectMessage()
    {
        $obj = $this->getDirectMessage();

        $this->directMessageSerializer->shouldReceive('serialize')->with($obj)->andReturn(new \stdClass());

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals('{}', $serialized);
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

        $delete = $this->getDelete();

        $this->deleteSerializer->shouldReceive('unserialize')->andReturn($delete)->once();

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($delete, $return);
    }

    /**
     * @test
     */
    public function testUnserializeUser()
    {
        $obj = new \stdClass();
        $obj->screen_name = 'douglas';

        $user = $this->getTwitterUser(1, 'douglas');

        $this->userSerializer->shouldReceive('unserialize')->andReturn($user)->once();

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($user, $return);
    }
} 