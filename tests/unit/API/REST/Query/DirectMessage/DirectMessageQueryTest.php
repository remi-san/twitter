<?php

namespace Twitter\Test\API\REST\Query\DirectMessage;

use Twitter\API\REST\Query\DirectMessage\DirectMessageQuery;

class DirectMessageQueryTest extends \PHPUnit_Framework_TestCase
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
        $query = new DirectMessageQuery(20, 5, 100, true, true);

        $this->assertEquals(
            [
                'include_entities' => 'true',
                'skip_status' => 'true',
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
        $query = new DirectMessageQuery(20, 5, 100, false, false);

        $this->assertEquals(
            [
                'include_entities' => 'false',
                'skip_status' => 'false',
                'count' => 20,
                'since_id' => 5,
                'max_id' => 100
            ],
            $query->toArray()
        );
    }
}
