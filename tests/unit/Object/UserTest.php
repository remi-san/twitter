<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterUser;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $id;

    /** @var  */
    private $name;

    /** @var string */
    private $screenName;

    /** @var string */
    private $lang;

    /** @var string */
    private $location;

    /** @var string */
    private $profileHttpUrl;

    /** @var string */
    private $profileHttpsUrl;

    public function setUp()
    {
        $faker = Factory::create();

        $this->id = $faker->randomNumber();
        $this->name = $faker->userName;
        $this->screenName = $faker->name;
        $this->lang = $faker->countryISOAlpha3;
        $this->location = $faker->country;
        $this->profileHttpUrl = $faker->url;
        $this->profileHttpsUrl = $faker->url;
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $user = TwitterUser::create(
            $this->id,
            $this->screenName,
            $this->name,
            $this->lang,
            $this->location,
            $this->profileHttpUrl,
            $this->profileHttpsUrl
        );

        $this->assertEquals($this->id, $user->getId());
        $this->assertEquals($this->name, $user->getName());
        $this->assertEquals($this->screenName, $user->getScreenName());
        $this->assertEquals($this->lang, $user->getLang());
        $this->assertEquals($this->location, $user->getLocation());
        $this->assertEquals($this->profileHttpUrl, $user->getProfileImageUrl());
        $this->assertEquals($this->profileHttpsUrl, $user->getProfileImageUrlHttps());
        $this->assertEquals('@'.$this->screenName, (string) $user);
    }
}
