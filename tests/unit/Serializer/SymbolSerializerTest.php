<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterSymbol;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterSymbolSerializer;
use Twitter\TwitterSerializable;

class SymbolSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $text;

    /** @var TwitterEntityIndices | Mock */
    private $indices;

    /** @var int[] */
    private $indicesObj;

    /** @var TwitterEntityIndicesSerializer | Mock */
    private $entityIndicesSerializer;

    /** @var TwitterSymbolSerializer */
    private $serializer;

    public function setUp()
    {
        $this->text = 'symbol';
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);

        $this->indicesObj = [42, 666];

        $this->entityIndicesSerializer = \Mockery::mock(TwitterEntityIndicesSerializer::class);

        $this->serializer = new TwitterSymbolSerializer($this->entityIndicesSerializer);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldNotSerializeWithIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->serialize($this->getInvalidObject());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $this->givenIndicesSerializerWillSerializeIndices();

        $serialized = $this->serializer->serialize($this->getValidObject());

        $this->assertEquals($this->text, $serialized->text);
        $this->assertEquals($this->indicesObj, $serialized->indices);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->givenIndicesSerializerWillUnserializeIndices();

        $symbol = $this->serializer->unserialize($this->getValidSerializedObject());

        $this->assertEquals($this->text, $symbol->getText());
        $this->assertEquals($this->indices, $symbol->getIndices());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($this->getInvalidSerializedObject());
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterSymbolSerializer::build();

        $this->assertInstanceOf(TwitterSymbolSerializer::class, $serializer);
    }

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return TwitterSymbol
     */
    private function getValidObject()
    {
        return TwitterSymbol::create($this->text, $this->indices);
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        $symbolObj = new \stdClass();
        $symbolObj->text = $this->text;
        $symbolObj->indices = $this->indicesObj;
        return $symbolObj;
    }

    /**
     * @return \stdClass
     */
    private function getInvalidSerializedObject()
    {
        return new \stdClass();
    }

    private function givenIndicesSerializerWillSerializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($this->indices)->andReturn($this->indicesObj);
    }

    private function givenIndicesSerializerWillUnserializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($this->indices);
    }
}
