<?php

namespace Twitter\Test\API\REST\Response;

use Twitter\API\REST\Response\ApiResponse;
use Twitter\API\REST\Response\HttpStatus;
use Twitter\API\REST\Response\UnlimitedApiRate;

class ApiResponseTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldHoldStatusRateUsageAndContent()
    {
        $status = new HttpStatus(200);
        $content = [];
        $rate = new UnlimitedApiRate();

        $response = new ApiResponse(
            $status,
            $content,
            $rate
        );

        $this->assertEquals($status, $response->getHttpStatus());
        $this->assertEquals($content, $response->getContent());
        $this->assertEquals($rate, $response->getRate());
    }
}
