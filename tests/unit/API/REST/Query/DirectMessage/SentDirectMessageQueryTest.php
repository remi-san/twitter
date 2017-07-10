<?php

namespace Twitter\Test\API\REST\Query\DirectMessage;

use Twitter\API\REST\Query\DirectMessage\SentDirectMessageQuery;

class SentDirectMessageQueryTest extends \PHPUnit_Framework_TestCase
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
        $query = new SentDirectMessageQuery(20, 5, 100, true, 1);

        $this->assertEquals(
            [
                'include_entities' => 'true',
                'page' => 1,
                'count' => 20,
                'since_id' => 5,
                'max_id' => 100
            ],
            $query->toArray()
        );
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalse()
    {
        $query = new SentDirectMessageQuery(20, 5, 100, false, 1);

        $this->assertEquals(
            [
                'include_entities' => 'false',
                'page' => 1,
                'count' => 20,
                'since_id' => 5,
                'max_id' => 100
            ],
            $query->toArray()
        );
    }
}
