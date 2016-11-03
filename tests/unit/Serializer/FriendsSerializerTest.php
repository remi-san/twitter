<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterFriends;
use Twitter\Serializer\TwitterFriendsSerializer;
use Twitter\TwitterSerializable;

class FriendsSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var int[] */
    private $friends;

    /** @var TwitterFriendsSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $this->friends = [1, 2, 3];

        $this->serviceUnderTest = new TwitterFriendsSerializer();
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
        $object = $this->getIllegalObject();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $obj = $this->getLegalObject();

        $serialized = $this->serviceUnderTest->serialize($obj);

        $this->assertEquals($this->friends, $serialized->friends);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $friendsObj = $this->getSerializedObject();

        $friends = $this->serviceUnderTest->unserialize($friendsObj);

        $this->assertEquals($this->friends, $friends->getFriends());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = $this->getIllegaleSerializedObject();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterFriendsSerializer::build();

        $this->assertInstanceOf(TwitterFriendsSerializer::class, $serializer);
    }

    /**
     * @return \stdClass
     */
    private function getSerializedObject()
    {
        $friendsObj = new \stdClass();
        $friendsObj->friends = $this->friends;
        return $friendsObj;
    }

    /**
     * @return TwitterFriends
     */
    private function getLegalObject()
    {
        return TwitterFriends::create($this->friends);
    }

    /**
     * @return \stdClass
     */
    private function getIllegaleSerializedObject()
    {
        return new \stdClass();
    }

    /**
     * @return TwitterSerializable
     */
    private function getIllegalObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }
}
