<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterDelete;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterDisconnect;
use Twitter\Object\TwitterEvent;
use Twitter\Object\TwitterFriends;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TwitterDeleteSerializer;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterDisconnectSerializer;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Serializer\TwitterFriendsSerializer;
use Twitter\Serializer\TwitterJsonSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\TwitterSerializable;

class TwitterJsonSerializerTest extends \PHPUnit_Framework_TestCase
{
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
        $this->eventTargetSerializer = \Mockery::mock(TwitterEventTargetSerializer::class);
        $this->directMessageSerializer = \Mockery::mock(TwitterDirectMessageSerializer::class);
        $this->eventSerializer = \Mockery::mock(TwitterEventSerializer::class);
        $this->friendsSerializer = \Mockery::mock(TwitterFriendsSerializer::class);
        $this->disconnectSerializer = \Mockery::mock(TwitterDisconnectSerializer::class);
        $this->deleteSerializer = \Mockery::mock(TwitterDeleteSerializer::class);
        $this->userSerializer = \Mockery::mock(TwitterUserSerializer::class);

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
    public function testIllegalSerialize()
    {
        $obj = $this->getInvalidObject();

        $this->givenNoSubSerializerCanSerializeObject($obj);

        $this->setExpectedException(\BadMethodCallException::class);

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testSerializeWithUser()
    {
        $obj = \Mockery::mock(TwitterUser::class);

        $this->givenUserSerializerCanSerializeObject($obj);
        $serializedObject = $this->givenUserSerializerWillSerializeObject($obj);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(json_encode($serializedObject), $serialized);
    }

    /**
     * @test
     */
    public function testSerializeWithEvent()
    {
        $obj = \Mockery::mock(TwitterEvent::class);

        $this->givenEventSerializerCanSerializeObject($obj);
        $serializedObject = $this->givenEventSerializerWillSerializeObject($obj);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(json_encode($serializedObject), $serialized);
    }

    /**
     * @test
     */
    public function testSerializeWithFriends()
    {
        $obj = \Mockery::mock(TwitterFriends::class);

        $this->givenFriendsSerializerCanSerializeObject($obj);
        $serializedObject = $this->givenFriendsSerializerWillSerializeObject($obj);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(json_encode($serializedObject), $serialized);
    }

    /**
     * @test
     */
    public function testSerializeWithDisconnect()
    {
        $obj = \Mockery::mock(TwitterDisconnect::class);

        $this->givenDisconnectSerializerCanSerializeObject($obj);
        $serializedObject = $this->givenDisconnectSerializerWillSerializeObject($obj);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(json_encode($serializedObject), $serialized);
    }

    /**
     * @test
     */
    public function testSerializeWithDelete()
    {
        $obj = \Mockery::mock(TwitterDelete::class);

        $this->givenDeleteSerializerCanSerializeObject($obj);
        $serializedObject = $this->givenDeleteSerializerWillSerializeObject($obj);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(json_encode($serializedObject), $serialized);
    }

    /**
     * @test
     */
    public function testSerializeWithTweet()
    {
        $obj = \Mockery::mock(Tweet::class);

        $this->givenTweetSerializerCanSerializeObject($obj);
        $serializedObject = $this->givenTweetSerializerWillSerializeObject($obj);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(json_encode($serializedObject), $serialized);
    }

    /**
     * @test
     */
    public function testSerializeWithDirectMessage()
    {
        $obj = \Mockery::mock(TwitterDirectMessage::class);

        $this->givenDirectMessageSerializerCanSerializeObject($obj);
        $serializedObject = $this->givenDirectMessageSerializerWillSerializeObject($obj);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(json_encode($serializedObject), $serialized);
    }

    /**
     * @test
     */
    public function itShouldUnserializeTweet()
    {
        $this->givenTweetSerializerCanUnserializeObject();
        $tweet = $this->givenTweetSerializerWillUnserializeObject();

        $return = $this->serializer->unserialize(json_encode(new \stdClass()));

        $this->assertEquals($tweet, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeDM()
    {
        $this->givenDirectMessageSerializerCanUnserializeObject();
        $dm = $this->givenDirectMessageSerializerWillUnserializeObject();

        $return = $this->serializer->unserialize(json_encode(new \stdClass()));

        $this->assertEquals($dm, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeEvent()
    {
        $this->givenEventSerializerCanUnserializeObject();
        $event = $this->givenEventSerializerWillUnserializeObject();

        $return = $this->serializer->unserialize(json_encode(new \stdClass()));

        $this->assertEquals($event, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeFriends()
    {
        $this->givenFriendsSerializerCanUnserializeObject();
        $friends = $this->givenFriendsSerializerWillUnserializeObject();

        $return = $this->serializer->unserialize(json_encode(new \stdClass()));

        $this->assertEquals($friends, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeDisconnect()
    {
        $this->givenDisconnectSerializerCanUnserializeObject();
        $disconnect = $this->givenDisconnectSerializerWillUnserializeObject();


        $return = $this->serializer->unserialize(json_encode(new \stdClass()));

        $this->assertEquals($disconnect, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeDelete()
    {
        $this->givenDeleteSerializerCanUnserializeObject();
        $delete = $this->givenDeleteSerializerWillUnserializeObject();

        $return = $this->serializer->unserialize(json_encode(new \stdClass()));

        $this->assertEquals($delete, $return);
    }

    /**
     * @test
     */
    public function itShouldUnserializeUser()
    {
        $this->givenUserSerializerCanUnserializeObject();
        $user = $this->givenUserSerializerWillUnserializeObject();

        $return = $this->serializer->unserialize(json_encode(new \stdClass()));

        $this->assertEquals($user, $return);
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->givenNoSubSerializerCanUnserializeObject();

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

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @param $obj
     */
    private function givenNoSubSerializerCanSerializeObject($obj)
    {
        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
    }

    /**
     * @param $obj
     */
    private function givenUserSerializerCanSerializeObject($obj)
    {
        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
    }

    /**
     * @param $obj
     *
     * @return \stdClass
     */
    private function givenUserSerializerWillSerializeObject($obj)
    {
        $serializedObj = new \stdClass();

        $this->userSerializer->shouldReceive('serialize')->with($obj)->andReturn($serializedObj);

        return $serializedObj;
    }

    /**
     * @param $obj
     */
    private function givenEventSerializerCanSerializeObject($obj)
    {
        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
    }

    /**
     * @param $obj
     *
     * @return \stdClass
     */
    private function givenEventSerializerWillSerializeObject($obj)
    {
        $serializedObj = new \stdClass();

        $this->eventSerializer->shouldReceive('serialize')->with($obj)->andReturn($serializedObj);

        return $serializedObj;
    }

    /**
     * @param $obj
     */
    private function givenFriendsSerializerCanSerializeObject($obj)
    {
        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
    }

    /**
     * @param $obj
     *
     * @return \stdClass
     */
    private function givenFriendsSerializerWillSerializeObject($obj)
    {
        $serializedObj = new \stdClass();

        $this->friendsSerializer->shouldReceive('serialize')->with($obj)->andReturn($serializedObj);

        return $serializedObj;
    }

    /**
     * @param $obj
     */
    private function givenDisconnectSerializerCanSerializeObject($obj)
    {
        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
    }

    /**
     * @param $obj
     *
     * @return \stdClass
     */
    private function givenDisconnectSerializerWillSerializeObject($obj)
    {
        $serializedObj = new \stdClass();

        $this->disconnectSerializer->shouldReceive('serialize')->with($obj)->andReturn($serializedObj);

        return $serializedObj;
    }

    /**
     * @param $obj
     */
    private function givenDeleteSerializerCanSerializeObject($obj)
    {
        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
    }

    /**
     * @param $obj
     *
     * @return \stdClass
     */
    private function givenDeleteSerializerWillSerializeObject($obj)
    {
        $serializedObj = new \stdClass();

        $this->deleteSerializer->shouldReceive('serialize')->with($obj)->andReturn($serializedObj);

        return $serializedObj;
    }

    /**
     * @param $obj
     */
    private function givenTweetSerializerCanSerializeObject($obj)
    {
        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
    }

    /**
     * @param $obj
     *
     * @return \stdClass
     */
    private function givenTweetSerializerWillSerializeObject($obj)
    {
        $serializedObj = new \stdClass();

        $this->eventTargetSerializer->shouldReceive('serialize')->with($obj)->andReturn($serializedObj);

        return $serializedObj;
    }

    /**
     * @param $obj
     */
    private function givenDirectMessageSerializerCanSerializeObject($obj)
    {
        $this->eventTargetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);
        $this->eventSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->friendsSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->deleteSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
        $this->userSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(false);
    }

    /**
     * @param $obj
     *
     * @return \stdClass
     */
    private function givenDirectMessageSerializerWillSerializeObject($obj)
    {
        $serializedObj = new \stdClass();

        $this->directMessageSerializer->shouldReceive('serialize')->with($obj)->andReturn($serializedObj);

        return $serializedObj;
    }

    private function givenTweetSerializerCanUnserializeObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);
    }

    /**
     * @return Tweet
     */
    private function givenTweetSerializerWillUnserializeObject()
    {
        $tweet = \Mockery::mock(Tweet::class);

        $this->eventTargetSerializer->shouldReceive('unserialize')->andReturn($tweet)->once();

        return $tweet;
    }

    /**
     * @return \Mockery\MockInterface
     */
    private function givenDirectMessageSerializerWillUnserializeObject()
    {
        $dm = \Mockery::mock(TwitterDirectMessage::class);

        $this->directMessageSerializer->shouldReceive('unserialize')->andReturn($dm)->once();
        return $dm;
    }

    private function givenDirectMessageSerializerCanUnserializeObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);
    }

    /**
     * @return \Twitter\Object\TwitterEvent
     */
    private function givenEventSerializerWillUnserializeObject()
    {
        $event = \Mockery::mock(TwitterEvent::class);

        $this->eventSerializer->shouldReceive('unserialize')->andReturn($event)->once();
        return $event;
    }

    private function givenEventSerializerCanUnserializeObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);
    }

    /**
     * @return \Twitter\Object\TwitterFriends
     */
    private function givenFriendsSerializerWillUnserializeObject()
    {
        $friends = \Mockery::mock(TwitterFriends::class);

        $this->friendsSerializer->shouldReceive('unserialize')->andReturn($friends)->once();
        return $friends;
    }

    private function givenFriendsSerializerCanUnserializeObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);
    }

    /**
     * @return \Twitter\Object\TwitterDisconnect
     */
    private function givenDisconnectSerializerWillUnserializeObject()
    {
        $disconnect = \Mockery::mock(TwitterDisconnect::class);

        $this->disconnectSerializer->shouldReceive('unserialize')->andReturn($disconnect)->once();
        return $disconnect;
    }

    private function givenDisconnectSerializerCanUnserializeObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);
    }

    /**
     * @return \Twitter\Object\TwitterDelete
     */
    private function givenDeleteSerializerWillUnserializeObject()
    {
        $delete = \Mockery::mock(TwitterDelete::class);

        $this->deleteSerializer->shouldReceive('unserialize')->andReturn($delete)->once();
        return $delete;
    }

    private function givenDeleteSerializerCanUnserializeObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(true);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);
    }

    /**
     * @return TwitterUser
     */
    private function givenUserSerializerWillUnserializeObject()
    {
        $user = \Mockery::mock(TwitterUser::class);

        $this->userSerializer->shouldReceive('unserialize')->andReturn($user)->once();
        return $user;
    }

    private function givenUserSerializerCanUnserializeObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(true);
    }

    private function givenNoSubSerializerCanUnserializeObject()
    {
        $this->eventTargetSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->directMessageSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->eventSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->friendsSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->disconnectSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->deleteSerializer->shouldReceive('canUnserialize')->andReturn(false);
        $this->userSerializer->shouldReceive('canUnserialize')->andReturn(false);
    }
}
