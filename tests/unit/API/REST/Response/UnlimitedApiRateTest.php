<?php

namespace Twitter\Test\API\REST\Response;

use Twitter\API\REST\Response\UnlimitedApiRate;

class UnlimitedApiRateTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldNeverReachLimit()
    {
        $rate = new UnlimitedApiRate();

        $this->assertEquals(-1, $rate->getLimit());
        $this->assertTrue($rate->canMakeAnotherCall());
        $this->assertEquals(
            \DateTimeImmutable::createFromFormat('U', time(), new \DateTimeZone('UTC')),
            $rate->nextWindow()
        );
    }
}
