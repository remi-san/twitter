<?php

namespace Twitter\Test\API\REST\Response;

use Twitter\API\REST\Response\LimitedApiRate;

class LimitedApiRateTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldTestANotReachedLimit()
    {
        $time = time();
        $rate = new LimitedApiRate(10, 2, $time);

        $this->assertEquals(10, $rate->getLimit());
        $this->assertTrue($rate->canMakeAnotherCall());
        $this->assertEquals(
            \DateTimeImmutable::createFromFormat('U', $time, new \DateTimeZone('UTC')),
            $rate->nextWindow()
        );
    }

    /**
     * @test
     */
    public function itShouldTestAReachedLimit()
    {
        $time = time();
        $rate = new LimitedApiRate(10, 0, $time);

        $this->assertEquals(10, $rate->getLimit());
        $this->assertFalse($rate->canMakeAnotherCall());
        $this->assertEquals(
            \DateTimeImmutable::createFromFormat('U', $time, new \DateTimeZone('UTC')),
            $rate->nextWindow()
        );
    }
}
