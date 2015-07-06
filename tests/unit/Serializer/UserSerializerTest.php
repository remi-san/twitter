<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterUserSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class UserSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterUserSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterUserSerializer();
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
        $user = $this->getCoordinates();

        $this->setExpectedException('\\InvalidArgumentException');

        $this->serializer->serialize($user);
    }

    /**
     * @test
     */
    public function testSerializeWithLegalObject()
    {
        $obj = $this->getTwitterUser(1, 'user');

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $userObj = new \stdClass();
        $userObj->id = 42;
        $userObj->screen_name = 'douglas';
        $userObj->name = 'Douglas Adams';
        $userObj->lang = 'fr';
        $userObj->location = 'Paris';
        $userObj->profile_background_image_url= 'http://background.url/image.jpg';
        $userObj->profile_background_image_url_https= 'https://background.url/image.jpg';

        $user = $this->serializer->unserialize($userObj);

        $this->assertEquals($userObj->id, $user->getId());
        $this->assertEquals($userObj->screen_name, $user->getScreenName());
        $this->assertEquals($userObj->name, $user->getName());
        $this->assertEquals($userObj->lang, $user->getLang());
        $this->assertEquals($userObj->location, $user->getLocation());
        $this->assertEquals($userObj->profile_background_image_url, $user->getProfileImageUrl());
        $this->assertEquals($userObj->profile_background_image_url_https, $user->getProfileImageUrlHttps());
    }
} 