<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterUserMention;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterUserMentionSerializer;
use Twitter\TwitterSerializable;

class UserMentionSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $screenName;

    /** @var TwitterEntityIndices */
    private $indices;

    /** @var array */
    private $indicesObj;

    /** @var TwitterEntityIndicesSerializer | Mock */
    private $entityIndicesSerializer;

    /** @var TwitterUserMentionSerializer */
    private $serializer;

    public function setUp()
    {
        $this->id = 42;
        $this->name = 'douglas';
        $this->screenName = 'douglas d';
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);

        $this->indicesObj = [];

        $this->entityIndicesSerializer = \Mockery::mock(TwitterEntityIndicesSerializer::class);

        $this->serializer = new TwitterUserMentionSerializer($this->entityIndicesSerializer);
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

        $this->assertEquals($this->id, $serialized->id);
        $this->assertEquals($this->screenName, $serialized->screen_name);
        $this->assertEquals($this->name, $serialized->name);
        $this->assertEquals($this->indicesObj, $serialized->indices);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->givenIndicesSerializerWillUnserializeIndices();

        $userMention = $this->serializer->unserialize($this->getValidSerializedObject());

        $this->assertEquals($this->id, $userMention->getId());
        $this->assertEquals($this->screenName, $userMention->getScreenName());
        $this->assertEquals($this->name, $userMention->getName());
        $this->assertEquals($this->indices, $userMention->getIndices());
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
        $serializer = TwitterUserMentionSerializer::build();

        $this->assertInstanceOf(TwitterUserMentionSerializer::class, $serializer);
    }

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return TwitterUserMention
     */
    private function getValidObject()
    {
        return TwitterUserMention::create($this->id, $this->screenName, $this->name, $this->indices);
    }

    private function givenIndicesSerializerWillSerializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($this->indices)->andReturn($this->indicesObj);
    }

    private function givenIndicesSerializerWillUnserializeIndices()
    {
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($this->indices);
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        $userMentionObj = new \stdClass();
        $userMentionObj->id = $this->id;
        $userMentionObj->screen_name = $this->screenName;
        $userMentionObj->name = $this->name;
        $userMentionObj->indices = $this->indicesObj;
        return $userMentionObj;
    }

    /**
     * @return \stdClass
     */
    private function getInvalidSerializedObject()
    {
        return new \stdClass();
    }
}
