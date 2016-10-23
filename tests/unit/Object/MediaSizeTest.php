<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterMediaSize;

class MediaSizeTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $name;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var string */
    private $resize;

    public function setUp()
    {
        $faker = Factory::create();

        $this->name = $faker->word;
        $this->width = $faker->randomNumber();
        $this->height = $faker->randomNumber();
        $this->resize = $faker->boolean() ? 'yes' : 'no';
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $mediaSize = TwitterMediaSize::create($this->name, $this->width, $this->height, $this->resize);

        $this->assertEquals($this->name, $mediaSize->getName());
        $this->assertEquals($this->width, $mediaSize->getWidth());
        $this->assertEquals($this->height, $mediaSize->getHeight());
        $this->assertEquals($this->resize, $mediaSize->getResize());
    }
}
