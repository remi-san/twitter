<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterEntityIndices;

class EntityIndicesTest extends \PHPUnit_Framework_TestCase
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
        $from = 0;
        $to = 42;

        $entityIndices = new TwitterEntityIndices($from, $to);

        $this->assertEquals($from, $entityIndices->getFrom());
        $this->assertEquals($to, $entityIndices->getTo());
    }
}
