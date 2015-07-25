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
        $friends = array(1, 2, 3);

        $obj = $this->getFriends();
        $obj->shouldReceive('getFriends')->andReturn($friends);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($friends, $serialized->friends);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $friendsObj = new \stdClass();
        $friendsObj->friends = array(1, 2, 3);

        $friends = $this->serializer->unserialize($friendsObj);

        $this->assertEquals($friendsObj->friends, $friends->getFriends());
    }
}
