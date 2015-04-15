<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Serializer\TwitterHashtagSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class HashtagSerializerTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterHashtagSerializer
     */
    private $serializer;

    /**
     * @var \Twitter\Serializer\TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->serializer = new TwitterHashtagSerializer($this->entityIndicesSerializer);
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
        $obj = $this->getHashTag('hashtag');

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $hashtagObj = new \stdClass();
        $hashtagObj->text = 'text';
        $hashtagObj->indices = array(42, 666);

        $indices = new TwitterEntityIndices(42, 666);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $hashtag = $this->serializer->unserialize($hashtagObj);

        $this->assertEquals($hashtagObj->text, $hashtag->getText());
        $this->assertEquals($indices, $hashtag->getIndices());
    }
} 