<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterHashtag;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterHashtagSerializer;
use Twitter\TwitterSerializable;

class HashtagSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $text;

    /** @var TwitterEntityIndices | Mock */
    private $indices;

    /** @var array */
    private $indicesObj;

    /** @var TwitterEntityIndicesSerializer | Mock */
    private $entityIndicesSerializer;

    /** @var TwitterHashtagSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $this->text = 'hashtag';
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);

        $this->indicesObj = [];

        $this->entityIndicesSerializer = \Mockery::mock(TwitterEntityIndicesSerializer::class);

        $this->serviceUnderTest = new TwitterHashtagSerializer($this->entityIndicesSerializer);
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

        $this->serviceUnderTest->serialize($this->getInvalidObject());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $this->givenSerializerWillSerializeIndices();

        $serialized = $this->serviceUnderTest->serialize($this->getValidObject());

        $this->assertEquals($this->text, $serialized->text);
        $this->assertEquals($this->indicesObj, $serialized->indices);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->givenSerializerWillUnserializeIndices();

        $hashtag = $this->serviceUnderTest->unserialize($this->getValidSerializedObject());

        $this->assertEquals($this->text, $hashtag->getText());
        $this->assertEquals($this->indices, $hashtag->getIndices());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($this->getInvalidSerializedObject());
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterHashtagSerializer::build();

        $this->assertInstanceOf(TwitterHashtagSerializer::class, $serializer);
    }

    private function givenSerializerWillSerializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($this->indices)->andReturn($this->indicesObj);
    }

    private function givenSerializerWillUnserializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($this->indices);
    }

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return TwitterHashtag
     */
    private function getValidObject()
    {
        return TwitterHashtag::create($this->text, $this->indices);
    }

    /**
     * @return \stdClass
     */
    private function getInvalidSerializedObject()
    {
        return new \stdClass();
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        $hashtagObj = new \stdClass();
        $hashtagObj->text = $this->text;
        $hashtagObj->indices = $this->indicesObj;
        return $hashtagObj;
    }
}
