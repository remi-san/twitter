<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterUser;

class UserTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $id = 42;
        $name = 'marcel';
        $screenName = 'Duchamp';
        $lang = 'fr';
        $location = 'Paris';
        $profileHttpUrl = 'http://my.profile.url';
        $profileHttpsUrl = 'https://my.profile.url';

        $user = new TwitterUser($id, $screenName, $name, $lang, $location, $profileHttpUrl, $profileHttpsUrl);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($screenName, $user->getScreenName());
        $this->assertEquals($lang, $user->getLang());
        $this->assertEquals($location, $user->getLocation());
        $this->assertEquals($profileHttpUrl, $user->getProfileImageUrl());
        $this->assertEquals($profileHttpsUrl, $user->getProfileImageUrlHttps());
        $this->assertEquals('@'.$screenName, $user->__toString());
    }
} 