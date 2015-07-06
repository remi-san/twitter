<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\TwitterDelete;
use Twitter\Serializer\TwitterDeleteSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class DeleteSerializerTest extends \PHPUnit_Framework_TestCase {
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
    public function testSerializeWithLegalObject()
    {
        $obj = $this->getDelete();

        $this->setExpectedException('\\BadMethodCallException');

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
} 