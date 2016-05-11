<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterHashtagSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class HashtagSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterHashtagSerializer
     */
    private $serializer;

    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->serializer = new TwitterHashtagSerializer($this->entityIndicesSerializer);
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
        $text = 'hashtag';

        $indices = $this->getIndices();
        $indicesObj = new \stdClass();
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($indices)->andReturn($indicesObj);

        $obj = $this->getHashTag($text);
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
        $hashtagObj = new \stdClass();
        $hashtagObj->text = 'text';
        $hashtagObj->indices = array(42, 666);

        $indices = TwitterEntityIndices::create(42, 666);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $hashtag = $this->serializer->unserialize($hashtagObj);

        $this->assertEquals($hashtagObj->text, $hashtag->getText());
        $this->assertEquals($indices, $hashtag->getIndices());
    }

    /**
     * @test
     */
    public function testIllegalUnserialize()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($obj);
    }

    /**
     * @test
     */
    public function testStaticBuilder()
    {
        $serializer = TwitterHashtagSerializer::build();

        $this->assertInstanceOf(TwitterHashtagSerializer::class, $serializer);
    }
}
