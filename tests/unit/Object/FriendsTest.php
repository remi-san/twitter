<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterFriends;

class FriendsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function testConstructor()
    {
        $list = array('42');

        $friends = new TwitterFriends($list);

        $this->assertEquals($list, $friends->getFriends());
        $this->assertEquals('Friends List', $friends->__toString());
    }

} 