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
    /** @var TwitterEntityIndicesSerializer | Mock */
    private $entityIndicesSerializer;

    /** @var TwitterHashtagSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
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
        $object = \Mockery::mock(TwitterSerializable::class);

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $text = 'hashtag';
        $indices = \Mockery::mock(TwitterEntityIndices::class);
        $indicesObj = new \stdClass();

        $this->entityIndicesSerializer->shouldReceive('serialize')->with($indices)->andReturn($indicesObj);

        $obj = TwitterHashtag::create($text, $indices);

        $serialized = $this->serviceUnderTest->serialize($obj);

        $this->assertEquals($text, $serialized->text);
        $this->assertEquals($indicesObj, $serialized->indices);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $hashtagObj = new \stdClass();
        $hashtagObj->text = 'text';
        $hashtagObj->indices = [];

        $indices = \Mockery::mock(TwitterEntityIndices::class);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $hashtag = $this->serviceUnderTest->unserialize($hashtagObj);

        $this->assertEquals($hashtagObj->text, $hashtag->getText());
        $this->assertEquals($indices, $hashtag->getIndices());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterHashtagSerializer::build();

        $this->assertInstanceOf(TwitterHashtagSerializer::class, $serializer);
    }
}
