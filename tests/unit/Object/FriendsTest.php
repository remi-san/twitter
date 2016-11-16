<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterFriends;

class FriendsTest extends \PHPUnit_Framework_TestCase
{
    /** @var int[] */
    private $list;

    public function setUp()
    {
        $faker = Factory::create();

        $this->list = [$faker->randomNumber()];
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldBuildTheObject()
    {
        $friends = TwitterFriends::create($this->list);

        $this->assertEquals($this->list, $friends->getFriends());
        $this->assertEquals('Friends List', (string) $friends);
    }
}
