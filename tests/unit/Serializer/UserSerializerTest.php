<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterUserSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class UserSerializerTest extends \PHPUnit_Framework_TestCase
{
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
    public function itShouldNotSerializeWithIllegalObject()
    {
        $user = $this->getCoordinates();

        $this->setExpectedException('\\InvalidArgumentException');

        $this->serializer->serialize($user);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $twitterUser = $this->getTwitterUser(1, 'user');
        $twitterUser->shouldReceive('getLang')->andReturn('en');
        $twitterUser->shouldReceive('getLocation')->andReturn('location');
        $twitterUser->shouldReceive('getProfileImageUrl')->andReturn('url');
        $twitterUser->shouldReceive('getProfileImageUrlHttps')->andReturn('surl');

        $serialized = $this->serializer->serialize($twitterUser);

        $this->assertEquals($twitterUser->getId(), $serialized->id);
        $this->assertEquals($twitterUser->getScreenName(), $serialized->screen_name);
        $this->assertEquals($twitterUser->getName(), $serialized->name);
        $this->assertEquals($twitterUser->getLang(), $serialized->lang);
        $this->assertEquals($twitterUser->getLocation(), $serialized->location);
        $this->assertEquals($twitterUser->getProfileImageUrl(), $serialized->profile_background_image_url);
        $this->assertEquals($twitterUser->getProfileImageUrlHttps(), $serialized->profile_background_image_url_https);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
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
        $serializer = TwitterUserSerializer::build();

        $this->assertInstanceOf(TwitterUserSerializer::class, $serializer);
    }
}
