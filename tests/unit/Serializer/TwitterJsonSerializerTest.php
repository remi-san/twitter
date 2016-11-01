<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
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
use Twitter\TwitterSerializable;

class TwitterJsonSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /** @var TwitterEventTargetSerializer | Mock */
    private $eventTargetSerializer;

    /** @var TwitterDirectMessageSerializer | Mock */
    private $directMessageSerializer;

    /** @var TwitterEventSerializer | Mock */
    private $eventSerializer;

    /** @var TwitterFriendsSerializer | Mock */
    private $friendsSerializer;

    /** @var TwitterDisconnectSerializer | Mock */
    private $disconnectSerializer;

    /** @var TwitterDeleteSerializer | Mock */
    private $deleteSerializer;

    /** @var TwitterUserSerializer | Mock */
    private $userSerializer;

    /** @var TwitterJsonSerializer */
    private $serializer;

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
    public function itShouldNotSerializeWithIllegalObject()
    {
        $object = \Mockery::mock(TwitterSerializable::class);

        $this->setExpectedException(\BadMethodCallException::class);

        $this->serializer->serialize($object);
    }

    /**
     * @test
     */
    public function testSerializeWithUser()
    {
        $obj = $this->getTwitterUser(1, 'douglas');

        $this->userSerializer->shouldReceive('serialize')->with($obj)->andReturn(new \stdClass());

        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);

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

        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);

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

        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals('{}', $serialized);
    }

    /**
     * @test
     */
    public function testIllegalSerialize()
    {
        $obj = \Mockery::mock(TwitterSerializable::class);

        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);

        $this->setExpectedException(\BadMethodCallException::class);

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function itShouldUnserializeTweet()
    {
        $obj = new \stdClass();
        $obj->text = 'text';
        $obj->user = new \stdClass();

        $tweet = $this->getTweet();

        $this->eventTargetSerializer->shouldReceive('unserialize')->andReturn($tweet)->once();

        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($tweet, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeDM()
    {
        $obj = new \stdClass();
        $obj->direct_message = new \stdClass();

        $dm = $this->getDirectMessage();

        $this->directMessageSerializer->shouldReceive('unserialize')->andReturn($dm)->once();

        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($dm, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeEvent()
    {
        $obj = new \stdClass();
        $obj->event = 'dm';

        $event = $this->getEvent();

        $this->eventSerializer->shouldReceive('unserialize')->andReturn($event)->once();

        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($event, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeFriends()
    {
        $obj = new \stdClass();
        $obj->friends = [];

        $friends = $this->getFriends();

        $this->friendsSerializer->shouldReceive('unserialize')->andReturn($friends)->once();

        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($friends, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeDisconnect()
    {
        $obj = new \stdClass();
        $obj->disconnect = new \stdClass();

        $disconnect = $this->getDisconnect();

        $this->disconnectSerializer->shouldReceive('unserialize')->andReturn($disconnect)->once();

        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($disconnect, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeDelete()
    {
        $obj = new \stdClass();
        $obj->delete = new \stdClass();

        $delete = $this->getDelete();

        $this->deleteSerializer->shouldReceive('unserialize')->andReturn($delete)->once();

        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($delete, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeUser()
    {
        $obj = new \stdClass();
        $obj->screen_name = 'douglas';

        $user = $this->getTwitterUser(1, 'douglas');

        $this->userSerializer->shouldReceive('unserialize')->andReturn($user)->once();

        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(true);

        $return = $this->serializer->unserialize(json_encode($obj));

        $this->assertEquals($user, $return);
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);

        $this->setExpectedException(\BadMethodCallException::class);

        $this->serializer->unserialize('');
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterJsonSerializer::build();

        $this->assertInstanceOf(TwitterJsonSerializer::class, $serializer);
    }
}
