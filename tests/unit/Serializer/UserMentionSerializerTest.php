<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterUserMentionSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class UserMentionSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterUserMentionSerializer
     */
    private $serializer;

    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    public function setUp()
    {
        $this->entityIndicesSerializer = $this->getEntityIndicesSerializer();
        $this->serializer = new TwitterUserMentionSerializer($this->entityIndicesSerializer);
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
        $id = 42;
        $name = 'douglas';

        $indices = $this->getIndices();
        $indicesObj = new \stdClass();
        $this->entityIndicesSerializer->shouldReceive('serialize')->with($indices)->andReturn($indicesObj);

        $obj = $this->getUserMention(42, $name);
        $obj->shouldReceive('getIndices')->andReturn($indices);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($id, $serialized->id);
        $this->assertEquals($name, $serialized->screen_name);
        $this->assertEquals($name, $serialized->name);
        $this->assertEquals($indicesObj, $serialized->indices);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $userMentionObj = new \stdClass();
        $userMentionObj->id = 42;
        $userMentionObj->screen_name = 'douglas';
        $userMentionObj->name = 'Douglas Adams';
        $userMentionObj->indices = array(42, 666);

        $indices = TwitterEntityIndices::create(42, 666);
        $this->entityIndicesSerializer->shouldReceive('unserialize')->andReturn($indices);

        $userMention = $this->serializer->unserialize($userMentionObj);

        $this->assertEquals($userMentionObj->id, $userMention->getId());
        $this->assertEquals($userMentionObj->screen_name, $userMention->getScreenName());
        $this->assertEquals($userMentionObj->name, $userMention->getName());
        $this->assertEquals($indices, $userMention->getIndices());
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
        $serializer = TwitterUserMentionSerializer::build();

        $this->assertInstanceOf(TwitterUserMentionSerializer::class, $serializer);
    }
}
