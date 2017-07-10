<?php

namespace Twitter\Test\API\Exception;

use Twitter\API\Exception\TwitterException;

class TwitterExceptionTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldCreateAnException()
    {
        $exception = new TwitterException('message');

        $this->assertEquals('message', $exception->getMessage());
    }
}
