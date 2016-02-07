<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterSymbolSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class SymbolSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterSymbolSerializer
     */
    private $serializer;

    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->serializer = new TwitterSymbolSerializer($this->entityIndicesSerializer);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testSerializeWithIllegalObject()
    {
        $user = $this->getTwitterUser(42, 'douglas');

        $this->setExpectedException('\\InvalidArgumentException');

        $this->serializer->serialize($user);
    }

    /**
     * @test
     */
    public function testSerializeWithLegalObject()
    {
        $text = 'symbol';

        $indices = $this->getIndices();
        $indicesObj = new \stdClass();
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($indices)->andReturn($indicesObj);

        $obj = $this->getSymbol();
        $obj->shouldReceive('getText')->andReturn($text);
        $obj->shouldReceive('getIndices')->andReturn($indices);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($text, $serialized->text);
        $this->assertEquals($indicesObj, $serialized->indices);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $symbolObj = new \stdClass();
        $symbolObj->text = 'text';
        $symbolObj->indices = array(42, 666);

        $indices = TwitterEntityIndices::create(42, 666);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $symbol = $this->serializer->unserialize($symbolObj);

        $this->assertEquals($symbolObj->text, $symbol->getText());
        $this->assertEquals($indices, $symbol->getIndices());
    }

    /**
     * @test
     */
    public function testStaticBuilder()
    {
        $serializer = TwitterSymbolSerializer::build();

        $this->assertInstanceOf(TwitterSymbolSerializer::class, $serializer);
    }
}
