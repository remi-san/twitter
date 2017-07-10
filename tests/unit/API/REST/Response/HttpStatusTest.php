<?php

namespace Twitter\Test\API\REST\Response;

use Twitter\API\REST\Response\HttpStatus;

class HttpStatusTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldBeAValidStatus()
    {
        $status = new HttpStatus(200);

        $this->assertFalse($status->isError());
    }

    /**
     * @test
     */
    public function itShouldBeAnInvalidStatus()
    {
        $status = new HttpStatus(500);

        $this->assertTrue($status->isError());
    }
}
