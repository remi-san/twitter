<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterFriends;

class FriendsTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $list = array('42');

        $friends = TwitterFriends::create($list);

        $this->assertEquals($list, $friends->getFriends());
        $this->assertEquals('Friends List', $friends->__toString());
    }
}
