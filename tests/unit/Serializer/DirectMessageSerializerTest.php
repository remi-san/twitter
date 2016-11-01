<?php
namespace Twitter\Test\Serializer;

use Faker\Factory;
use Mockery\Mock;
use Twitter\Object\TwitterDate;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterEntitiesSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\TwitterMessageId;
use Twitter\TwitterSerializable;

class DirectMessageSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $id;

    /** @var string */
    private $text;

    /** @var \DateTimeInterface */
    private $date;


    /** @var object */
    private $serializedSender;

    /** @var object */
    private $serializedRecipient;

    /** @var object */
    private $serializedEntities;


    /** @var TwitterUser | Mock */
    private $sender;

    /** @var TwitterUser | Mock */
    private $recipient;

    /** @var TwitterEntities | Mock */
    private $twitterEntities;


    /** @var object */
    private $serializedDirectMessage;

    /** @var TwitterDirectMessage */
    private $directMessage;


    /** @var TwitterUserSerializer | Mock */
    private $userSerializer;

    /** @var TwitterEntitiesSerializer | Mock */
    private $entitiesSerializer;


    /** @var TwitterDirectMessageSerializer */
    private $serviceUnderTest;


    public function setUp()
    {
        $faker = Factory::create();

        $this->id = $faker->randomNumber();
        $this->text = $faker->text();
        $this->date = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        $this->serializedSender = new \stdClass();
        $this->serializedRecipient = new \stdClass();
        $this->serializedEntities = new \stdClass();

        $this->sender = \Mockery::mock(TwitterUser::class);
        $this->recipient = \Mockery::mock(TwitterUser::class);
        $this->twitterEntities = \Mockery::mock(TwitterEntities::class);

        $this->serializedDirectMessage = $this->getSerializedDirectMessage();
        $this->directMessage = TwitterDirectMessage::create(
            TwitterMessageId::create($this->id),
            $this->sender,
            $this->recipient,
            $this->text,
            $this->date,
            $this->twitterEntities
        );

        $this->userSerializer = \Mockery::mock(TwitterUserSerializer::class);
        $this->entitiesSerializer = \Mockery::mock(TwitterEntitiesSerializer::class);

        $this->serviceUnderTest = new TwitterDirectMessageSerializer(
            $this->userSerializer,
            $this->entitiesSerializer
        );
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
    public function itShouldSerializeWithLegalDirectMessage()
    {
        $this->itWillSerializeSender();
        $this->itWillSerializeRecipient();
        $this->itWillSerializeEntities();

        $serialized = $this->serviceUnderTest->serialize($this->directMessage)->direct_message;

        $this->assertEquals($this->id, $serialized->id);
        $this->assertEquals($this->serializedSender, $serialized->sender);
        $this->assertEquals($this->serializedRecipient, $serialized->recipient);
        $this->assertEquals($this->text, $serialized->text);
        $this->assertEquals($this->date->format(TwitterDate::FORMAT), $serialized->created_at);
        $this->assertEquals($this->serializedEntities, $serialized->entities);
    }

    /**
     * @test
     */
    public function itShouldUnserializeDM()
    {
        $this->itWillUnserializeSender();
        $this->itWillUnserializeRecipient();
        $this->itCanUnserializeEntities();
        $this->itWillUnserializeEntities();

        $innerDirectMessage = $this->serializedDirectMessage->direct_message;
        $directMessage = $this->serviceUnderTest->unserialize($this->serializedDirectMessage);

        $this->assertEquals((string) $innerDirectMessage->id, (string) $directMessage->getId());
        $this->assertEquals($this->sender, $directMessage->getSender());
        $this->assertEquals($this->recipient, $directMessage->getRecipient());
        $this->assertEquals($innerDirectMessage->text, $directMessage->getText());
        $this->assertEquals($innerDirectMessage->created_at, $directMessage->getDate()->format(TwitterDate::FORMAT));
        $this->assertEquals($this->twitterEntities, $directMessage->getEntities());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = $this->getIllegalSerializedObject();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterDirectMessageSerializer::build();

        $this->assertInstanceOf(TwitterDirectMessageSerializer::class, $serializer);
    }

    /**
     * @return \stdClass
     */
    private function getSerializedDirectMessage()
    {
        $dmObj = new \stdClass();
        $dmObj->id = $this->id;
        $dmObj->sender = $this->serializedSender;
        $dmObj->recipient = $this->serializedRecipient;
        $dmObj->text = $this->text;
        $dmObj->created_at = $this->date->format(TwitterDate::FORMAT);
        $dmObj->entities = $this->serializedEntities;

        $superDmObject = new \stdClass();
        $superDmObject->direct_message = $dmObj;

        return $superDmObject;
    }

    /**
     * @return \stdClass
     */
    private function getIllegalSerializedObject()
    {
        return new \stdClass();
    }

    private function itWillSerializeSender()
    {
        $this->userSerializer
            ->shouldReceive('serialize')
            ->with($this->sender)
            ->andReturn($this->serializedSender);
    }

    private function itWillSerializeRecipient()
    {
        $this->userSerializer
            ->shouldReceive('serialize')
            ->with($this->recipient)
            ->andReturn($this->serializedRecipient);
    }

    private function itWillSerializeEntities()
    {
        $this->entitiesSerializer
            ->shouldReceive('serialize')
            ->with($this->twitterEntities)
            ->andReturn($this->serializedEntities);
    }

    private function itWillUnserializeSender()
    {
        $this->userSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedSender)
            ->andReturn($this->sender);
    }

    private function itWillUnserializeRecipient()
    {
        $this->userSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedRecipient)
            ->andReturn($this->recipient);
    }

    private function itWillUnserializeEntities()
    {
        $this->entitiesSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedEntities)
            ->andReturn($this->twitterEntities);
    }

    private function itCanUnserializeEntities()
    {
        $this->entitiesSerializer
            ->shouldReceive('canUnserialize')
            ->with($this->serializedEntities)
            ->andReturn(true);
    }
}
