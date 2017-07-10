<?php

namespace Twitter\Test\API\REST\Query\Friends;

use Twitter\API\REST\DTO\UserIdentifier;
use Twitter\API\REST\Query\Friends\FriendsListQuery;

class FriendsListQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Init
     */
    public function setUp()
    {
    }

    /**
     * Close
     */
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldSerializeWithTrueAndIdIdentifier()
    {
        $query = new FriendsListQuery(
            UserIdentifier::fromId(33),
            20,
            2,
            true,
            true
        );

        $this->assertEquals(
            [
                'user_id' => 33,
                'count' => 20,
                'cursor' => 2,
                'skip_status' => 'true',
                'include_entities' => 'true'
            ],
            $query->toArray()
        );
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalseAndNameIdentifier()
    {
        $query = new FriendsListQuery(
            UserIdentifier::fromScreenName('username'),
            20,
            2,
            false,
            false
        );

        $this->assertEquals(
            [
                'screen_name' => 'username',
                'count' => 20,
                'cursor' => 2,
                'skip_status' => 'false',
                'include_entities' => 'false'
            ],
            $query->toArray()
        );
    }
}
