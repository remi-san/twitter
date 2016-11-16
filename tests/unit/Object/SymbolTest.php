<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterSymbol;

class SymbolTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $text;

    /** @var TwitterEntityIndices */
    private $indices;

    public function setUp()
    {
        $faker = Factory::create();

        $this->text = $faker->word;
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);
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
        $symbol = TwitterSymbol::create($this->text, $this->indices);

        $this->assertEquals($this->text, $symbol->getText());
        $this->assertEquals($this->indices, $symbol->getIndices());
    }
}
