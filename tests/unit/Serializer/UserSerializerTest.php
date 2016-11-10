<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterUser;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\TwitterSerializable;

class UserSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $screenName;

    /** @var string */
    private $lang;

    /** @var string */
    private $location;

    /** @var string */
    private $profileImageUrl;

    /** @var string */
    private $profileImageUrlHttps;

    /** @var TwitterUserSerializer */
    private $serializer;

    public function setUp()
    {
        $this->id = 1;
        $this->screenName = 'douglas';
        $this->name = 'Douglas Adams';
        $this->lang = 'fr';
        $this->location = 'Paris';
        $this->profileImageUrl = 'http://my.image';
        $this->profileImageUrlHttps = 'https://my.image';

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
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->serialize($this->getInvalidObject());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $serialized = $this->serializer->serialize($this->getValidObject());

        $this->assertEquals($this->id, $serialized->id);
        $this->assertEquals($this->screenName, $serialized->screen_name);
        $this->assertEquals($this->name, $serialized->name);
        $this->assertEquals($this->lang, $serialized->lang);
        $this->assertEquals($this->location, $serialized->location);
        $this->assertEquals($this->profileImageUrl, $serialized->profile_background_image_url);
        $this->assertEquals($this->profileImageUrlHttps, $serialized->profile_background_image_url_https);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $user = $this->serializer->unserialize($this->getValidSerializedObject());

        $this->assertEquals($this->id, $user->getId());
        $this->assertEquals($this->screenName, $user->getScreenName());
        $this->assertEquals($this->name, $user->getName());
        $this->assertEquals($this->lang, $user->getLang());
        $this->assertEquals($this->location, $user->getLocation());
        $this->assertEquals($this->profileImageUrl, $user->getProfileImageUrl());
        $this->assertEquals($this->profileImageUrlHttps, $user->getProfileImageUrlHttps());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($this->getInvalidSerializedObject());
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterUserSerializer::build();

        $this->assertInstanceOf(TwitterUserSerializer::class, $serializer);
    }

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return TwitterUser
     */
    private function getValidObject()
    {
        $twitterUser = TwitterUser::create(
            $this->id,
            $this->screenName,
            $this->name,
            $this->lang,
            $this->location,
            $this->profileImageUrl,
            $this->profileImageUrlHttps
        );
        return $twitterUser;
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        $userObj = new \stdClass();
        $userObj->id = $this->id;
        $userObj->screen_name = $this->screenName;
        $userObj->name = $this->name;
        $userObj->lang = $this->lang;
        $userObj->location = $this->location;
        $userObj->profile_background_image_url = $this->profileImageUrl;
        $userObj->profile_background_image_url_https = $this->profileImageUrlHttps;
        return $userObj;
    }

    /**
     * @return \stdClass
     */
    private function getInvalidSerializedObject()
    {
        return new \stdClass();
    }
}
