<?php

namespace Twitter\Test\API\REST\DTO;

use Twitter\API\REST\DTO\Coordinates;

class CoordinatesTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldSerialize()
    {
        $coordinates = new Coordinates(54, 42);

        $this->assertEquals(['lat' => 54, 'long' => 42], $coordinates->toArray());
    }
}
