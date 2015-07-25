<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterCoordinates;

class CoordinatesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $long = 3.14;
        $lat = 42;
        $type = 'point';

        $coordinates = new TwitterCoordinates($long, $lat, $type);

        $this->assertEquals($long, $coordinates->getLongitude());
        $this->assertEquals($lat, $coordinates->getLatitude());
        $this->assertEquals($type, $coordinates->getType());
    }
}
