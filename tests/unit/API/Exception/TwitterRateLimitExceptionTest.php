<?php

namespace Twitter\Test\API\Exception;

use Twitter\API\Exception\TwitterException;
use Twitter\API\Exception\TwitterRateLimitException;
use Twitter\API\REST\DTO\Coordinates;
use Twitter\API\REST\Response\LimitedApiRate;
use Twitter\API\REST\Response\UnlimitedApiRate;

class TwitterRateLimitExceptionTest extends \PHPUnit_Framework_TestCase
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
        $rate = new LimitedApiRate(5, 0, time());
        $exception = TwitterRateLimitException::create('category', $rate);

        $this->assertEquals(
            'You have reached rate limit. You cannot make an API call yet.',
            $exception->getMessage()
        );

        $this->assertEquals('category', $exception->getCategory());
        $this->assertEquals($rate->nextWindow(), $exception->nextWindow());
    }
}
