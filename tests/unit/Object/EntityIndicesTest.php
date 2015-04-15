<?php
namespace Twitter\Test\Object;

class EntityIndicesTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function testConstructor()
    {
        $from = 0;
        $to = 42;

        $entityIndices = new \Twitter\Object\TwitterEntityIndices($from, $to);

        $this->assertEquals($from, $entityIndices->getFrom());
        $this->assertEquals($to, $entityIndices->getTo());
    }
} 