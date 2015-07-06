<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterSymbol;
use Twitter\Test\Mock\TwitterObjectMocker;

class SymbolTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $text = 'symbol';
        $indices = $this->getIndices();

        $symbol = new TwitterSymbol($text, $indices);

        $this->assertEquals($text, $symbol->getText());
        $this->assertEquals($indices, $symbol->getIndices());
    }
} 