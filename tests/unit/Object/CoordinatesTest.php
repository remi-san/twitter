<?php
namespace Twitter\Test\Object\Doctrine;

use Faker\Factory;
use Twitter\Object\TwitterCoordinates;

class CoordinatesTest extends \PHPUnit_Framework_TestCase
{
    /** @var float */
    private $long;

    /** @var float */
    private $lat;

    /** @var string */
    private $type;

    public function setUp()
    {
        $faker = Factory::create();

        $this->long = $faker->longitude;
        $this->lat = $faker->latitude;
        $this->type = $faker->word;
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldBuildTheObject()
    {
        $coordinates = TwitterCoordinates::create($this->long, $this->lat, $this->type);

        $this->assertEquals($this->long, $coordinates->getLongitude());
        $this->assertEquals($this->lat, $coordinates->getLatitude());
        $this->assertEquals($this->type, $coordinates->getType());
    }
}
