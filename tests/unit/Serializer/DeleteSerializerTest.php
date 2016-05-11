<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterDelete;
use Twitter\Serializer\TwitterDeleteSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class DeleteSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterDeleteSerializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = new TwitterDeleteSerializer();
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
    public function testSerializeWithLegalDM()
    {
        $id = 42;
        $userId = 666;
        $type = TwitterDelete::DM;
        $date = new \DateTime();

        $obj = $this->getDelete();
        $obj->shouldReceive('getId')->andReturn($id);
        $obj->shouldReceive('getUserId')->andReturn($userId);
        $obj->shouldReceive('getType')->andReturn($type);
        $obj->shouldReceive('getDate')->andReturn($date);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($id, $serialized->delete->direct_message->id);
        $this->assertEquals($userId, $serialized->delete->direct_message->user_id);
        $this->assertEquals($date->getTimestamp()*1000, $serialized->delete->timestamp_ms);
    }

    /**
     * @test
     */
    public function testSerializeWithLegalTweet()
    {
        $id = 42;
        $userId = 666;
        $type = TwitterDelete::TWEET;
        $date = new \DateTime();

        $obj = $this->getDelete();
        $obj->shouldReceive('getId')->andReturn($id);
        $obj->shouldReceive('getUserId')->andReturn($userId);
        $obj->shouldReceive('getType')->andReturn($type);
        $obj->shouldReceive('getDate')->andReturn($date);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($id, $serialized->delete->status->id);
        $this->assertEquals($userId, $serialized->delete->status->user_id);
        $this->assertEquals($date->getTimestamp()*1000, $serialized->delete->timestamp_ms);
    }

    /**
     * @test
     */
    public function testSerializeWithIllegalType()
    {
        $id = 42;
        $userId = 666;
        $type = null;
        $date = new \DateTime();

        $obj = $this->getDelete();
        $obj->shouldReceive('getId')->andReturn($id);
        $obj->shouldReceive('getUserId')->andReturn($userId);
        $obj->shouldReceive('getType')->andReturn($type);
        $obj->shouldReceive('getDate')->andReturn($date);

        $this->setExpectedException('\InvalidArgumentException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserializeDeleteTweet()
    {
        $date = new \DateTime();

        $obj = new \stdClass();
        $obj->delete = new \stdClass();
        $obj->delete->status = new \stdClass();
        $obj->delete->status->id = 42;
        $obj->delete->status->user_id = 666;
        $obj->delete->timestamp_ms = $date->getTimestamp()*1000;

        $delete = $this->serializer->unserialize($obj);

        $this->assertEquals(TwitterDelete::TWEET, $delete->getType());
        $this->assertEquals($obj->delete->status->id, $delete->getId());
        $this->assertEquals($obj->delete->status->user_id, $delete->getUserId());
        $this->assertEquals($date, $delete->getDate());
    }

    /**
     * @test
     */
    public function testUnserializeDeleteDM()
    {
        $date = new \DateTime();

        $obj = new \stdClass();
        $obj->delete = new \stdClass();
        $obj->delete->direct_message = new \stdClass();
        $obj->delete->direct_message->id = 42;
        $obj->delete->direct_message->user_id = 666;
        $obj->delete->timestamp_ms = $date->getTimestamp()*1000;

        $delete = $this->serializer->unserialize($obj);

        $this->assertEquals(TwitterDelete::DM, $delete->getType());
        $this->assertEquals($obj->delete->direct_message->id, $delete->getId());
        $this->assertEquals($obj->delete->direct_message->user_id, $delete->getUserId());
        $this->assertEquals($date, $delete->getDate());
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
        $serializer = TwitterDeleteSerializer::build();

        $this->assertInstanceOf(TwitterDeleteSerializer::class, $serializer);
    }
}
