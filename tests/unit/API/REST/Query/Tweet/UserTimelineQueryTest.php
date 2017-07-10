<?php

namespace Twitter\Test\API\REST\Query\Tweet;

use Twitter\API\REST\DTO\UserIdentifier;
use Twitter\API\REST\Query\Tweet\UserTimelineQuery;

class UserTimelineQueryTest extends \PHPUnit_Framework_TestCase
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
        $query = new UserTimelineQuery(
            UserIdentifier::fromId(33),
            20,
            4,
            33,
            true,
            true,
            true
        );

        $this->assertEquals(
            [
                'user_id' => 33,
                'include_rts' => 'true',
                'trim_user' => 'true',
                'exclude_replies' => 'true',
                'count' => 20,
                'since_id' => 4,
                'max_id' => 33
            ],
            $query->toArray()
        );
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalseAndNameIdentifier()
    {
        $query = new UserTimelineQuery(
            UserIdentifier::fromScreenName('username'),
            20,
            4,
            33,
            false,
            false,
            false
        );

        $this->assertEquals(
            [
                'screen_name' => 'username',
                'include_rts' => 'false',
                'trim_user' => 'false',
                'exclude_replies' => 'false',
                'count' => 20,
                'since_id' => 4,
                'max_id' => 33
            ],
            $query->toArray()
        );
    }
}
