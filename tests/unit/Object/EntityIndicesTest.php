<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterEntityIndices;

class EntityIndicesTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $from;

    /** @var int */
    private $to;

    public function setUp()
    {
        $faker = Factory::create();

        $this->from = $faker->randomNumber();
        $this->to = $faker->numberBetween($this->from);
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
        $entityIndices = TwitterEntityIndices::create($this->from, $this->to);

        $this->assertEquals($this->from, $entityIndices->getFrom());
        $this->assertEquals($this->to, $entityIndices->getTo());
    }
}
