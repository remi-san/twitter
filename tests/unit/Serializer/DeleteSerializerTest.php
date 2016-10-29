<?php
namespace Twitter\Test\Serializer;

use Faker\Factory;
use Twitter\Object\TwitterDelete;
use Twitter\Serializer\TwitterDeleteSerializer;
use Twitter\TwitterSerializable;

class DeleteSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $id;

    /** @var int */
    private $userId;

    /** @var \DateTimeInterface */
    private $date;

    /** @var TwitterDelete */
    private $deleteDirectMessage;

    /** @var TwitterDelete */
    private $twitterMessage;

    /** @var TwitterDeleteSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $faker = Factory::create();

        $this->id = $faker->randomNumber();
        $this->userId = $faker->randomNumber();
        $this->date = new \DateTimeImmutable();

        $this->deleteDirectMessage = TwitterDelete::create(TwitterDelete::DM, $this->id, $this->userId, $this->date);
        $this->twitterMessage = TwitterDelete::create(TwitterDelete::TWEET, $this->id, $this->userId, $this->date);

        $this->serviceUnderTest = new TwitterDeleteSerializer();
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
        $this->setExpectedException('\\InvalidArgumentException');

        $this->serviceUnderTest->serialize(\Mockery::mock(TwitterSerializable::class));
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalDeleteDirectMessage()
    {
        $serialized = $this->serviceUnderTest->serialize($this->deleteDirectMessage);

        $this->assertEquals($this->id, $serialized->delete->direct_message->id);
        $this->assertEquals($this->userId, $serialized->delete->direct_message->user_id);
        $this->assertEquals($this->date->getTimestamp()*1000, $serialized->delete->timestamp_ms);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalDirectTweet()
    {
        $serialized = $this->serviceUnderTest->serialize($this->twitterMessage);

        $this->assertEquals($this->id, $serialized->delete->status->id);
        $this->assertEquals($this->userId, $serialized->delete->status->user_id);
        $this->assertEquals($this->date->getTimestamp()*1000, $serialized->delete->timestamp_ms);
    }

    /**
     * @test
     */
    public function itShouldUnserializeDeleteTweet()
    {
        $delete = $this->serviceUnderTest->unserialize($this->getSerializedTweetDelete());

        $this->assertEquals(TwitterDelete::TWEET, $delete->getType());
        $this->assertEquals($this->id, $delete->getId());
        $this->assertEquals($this->userId, $delete->getUserId());
        $this->assertEquals($this->date, $delete->getDate());
    }

    /**
     * @test
     */
    public function itShouldUnserializeDeleteDM()
    {
        $delete = $this->serviceUnderTest->unserialize($this->getSerializedDirectMessageDelete());

        $this->assertEquals(TwitterDelete::DM, $delete->getType());
        $this->assertEquals($this->id, $delete->getId());
        $this->assertEquals($this->userId, $delete->getUserId());
        $this->assertEquals($this->date, $delete->getDate());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($this->getIllegalSerializedObject());
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterDeleteSerializer::build();

        $this->assertInstanceOf(TwitterDeleteSerializer::class, $serializer);
    }

    /**
     * @return \stdClass
     */
    private function getSerializedTweetDelete()
    {
        $serializedTweetDelete = new \stdClass();
        $serializedTweetDelete->delete = new \stdClass();
        $serializedTweetDelete->delete->status = new \stdClass();
        $serializedTweetDelete->delete->status->id = $this->id;
        $serializedTweetDelete->delete->status->user_id = $this->userId;
        $serializedTweetDelete->delete->timestamp_ms = $this->date->getTimestamp() * 1000;
        return $serializedTweetDelete;
    }

    /**
     * @return \stdClass
     */
    private function getSerializedDirectMessageDelete()
    {
        $serializedTweetDelete = new \stdClass();
        $serializedTweetDelete->delete = new \stdClass();
        $serializedTweetDelete->delete->direct_message = new \stdClass();
        $serializedTweetDelete->delete->direct_message->id = $this->id;
        $serializedTweetDelete->delete->direct_message->user_id = $this->userId;
        $serializedTweetDelete->delete->timestamp_ms = $this->date->getTimestamp() * 1000;
        return $serializedTweetDelete;
    }

    /**
     * @return \stdClass
     */
    private function getIllegalSerializedObject()
    {
        return new \stdClass();
    }
}
