<?php

namespace Twitter\Test\API\REST\Query\Stream;

use Twitter\API\REST\Query\Stream\UserStreamQuery;

class UserStreamQueryTest extends \PHPUnit_Framework_TestCase
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
        $query = new UserStreamQuery(true, true);

        $this->assertEquals(
            [
                'with' => 'user',
                'replies' => 'all'
            ],
            $query->toArray()
        );
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalse()
    {
        $query = new UserStreamQuery(false, false);

        $this->assertEquals(
            [],
            $query->toArray()
        );
    }
}
