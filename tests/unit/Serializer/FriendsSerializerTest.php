<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterFriendsSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class FriendsSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterFriendsSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterFriendsSerializer();
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
        $user = $this->getTwitterUser(42, 'douglas');

        $this->setExpectedException('\\InvalidArgumentException');

        $this->serializer->serialize($user);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $friends = array(1, 2, 3);

        $obj = $this->getFriends();
        $obj->shouldReceive('getFriends')->andReturn($friends);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($friends, $serialized->friends);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $friendsObj = new \stdClass();
        $friendsObj->friends = array(1, 2, 3);

        $friends = $this->serializer->unserialize($friendsObj);

        $this->assertEquals($friendsObj->friends, $friends->getFriends());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterFriendsSerializer::build();

        $this->assertInstanceOf(TwitterFriendsSerializer::class, $serializer);
    }
}
