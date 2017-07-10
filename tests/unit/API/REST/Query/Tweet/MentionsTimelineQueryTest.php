<?php

namespace Twitter\Test\API\REST\Query\Tweet;

use Twitter\API\REST\Query\Tweet\MentionsTimelineQuery;

class MentionsTimelineQueryTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldSerializeWithTrue()
    {
        $query = new MentionsTimelineQuery(
            20,
            3,
            42,
            true,
            true,
            true
        );

        $this->assertEquals(
            [
                'include_rts' => 'true',
                'trim_user' => 'true',
                'include_entities' => 'true',
                'count' => 20,
                'since_id' => 3,
                'max_id' => 42
            ],
            $query->toArray()
        );
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalse()
    {
        $query = new MentionsTimelineQuery(
            20,
            3,
            42,
            false,
            false,
            false
        );

        $this->assertEquals(
            [
                'include_rts' => 'false',
                'trim_user' => 'false',
                'include_entities' => 'false',
                'count' => 20,
                'since_id' => 3,
                'max_id' => 42
            ],
            $query->toArray()
        );
    }
}
