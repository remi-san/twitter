<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterFriendsSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class FriendsSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterFriendsSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterFriendsSerializer();
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
        $obj = $this->getFriends();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
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