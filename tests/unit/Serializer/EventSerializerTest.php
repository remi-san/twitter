<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterDate;
use Twitter\Object\TwitterEvent;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\TwitterEventTarget;
use Twitter\TwitterSerializable;

class EventSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $type;

    /** @var \DateTimeInterface */
    private $date;

    /** @var TwitterUser | Mock */
    private $source;

    /** @var TwitterUser | Mock */
    private $target;

    /** @var TwitterEventTarget | Mock */
    private $targetObject;

    /** @var object */
    private $serializedSource;

    /** @var object */
    private $serializedTarget;

    /** @var object */
    private $serializedTargetObject;

    /** @var TwitterUserSerializer | Mock */
    private $userSerializer;

    /** @var TwitterEventTargetSerializer | Mock */
    private $eventTargetSerializer;

    /** @var TwitterEventSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $this->type = TwitterEvent::FAVORITE;
        $this->date = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->source = \Mockery::mock(TwitterUser::class);
        $this->target = \Mockery::mock(TwitterUser::class);
        $this->targetObject = \Mockery::mock(TwitterEventTarget::class);

        $this->serializedSource = new \stdClass();
        $this->serializedTarget = new \stdClass();
        $this->serializedTargetObject = new \stdClass();

        $this->userSerializer = \Mockery::mock(TwitterUserSerializer::class);
        $this->eventTargetSerializer = \Mockery::mock(TwitterEventTargetSerializer::class);

        $this->serviceUnderTest = new TwitterEventSerializer($this->userSerializer, $this->eventTargetSerializer);
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
        $object = $this->getIllegalObject();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $this->itWillSerializeSource();
        $this->itWillSerializeTarget();
        $this->itWillWSerializeTargetObject();

        $obj = $this->getEvent();

        $serialized = $this->serviceUnderTest->serialize($obj);

        $this->assertEquals($this->type, $serialized->event);
        $this->assertEquals($this->date->format(TwitterDate::FORMAT), $serialized->created_at);
        $this->assertEquals($this->serializedSource, $serialized->source);
        $this->assertEquals($this->serializedTarget, $serialized->target);
        $this->assertEquals($this->serializedTargetObject, $serialized->target_object);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->itWillUnserializeSource();
        $this->itWillUnserializeTarget();
        $this->itWillUnserializeTargetObject();

        $eventObj = $this->getSerializedEvent();

        $event = $this->serviceUnderTest->unserialize($eventObj);

        $this->assertEquals($eventObj->event, $event->getType());
        $this->assertEquals($this->source, $event->getSource());
        $this->assertEquals($this->target, $event->getTarget());
        $this->assertEquals($this->targetObject, $event->getObject());
        $this->assertEquals(new \DateTimeImmutable($eventObj->created_at), $event->getDate());
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
        $serializer = TwitterEventSerializer::build();

        $this->assertInstanceOf(TwitterEventSerializer::class, $serializer);
    }

    private function itWillSerializeSource()
    {
        $this->userSerializer->shouldReceive('serialize')->with($this->source)->andReturn($this->serializedSource);
    }

    private function itWillSerializeTarget()
    {
        $this->userSerializer->shouldReceive('serialize')->with($this->target)->andReturn($this->serializedTarget);
    }

    private function itWillWSerializeTargetObject()
    {
        $this->eventTargetSerializer
            ->shouldReceive('serialize')
            ->with($this->targetObject)
            ->andReturn($this->serializedTargetObject);
    }

    private function itWillUnserializeSource()
    {
        $this->userSerializer->shouldReceive('unserialize')->with($this->serializedSource)->andReturn($this->source);
    }

    private function itWillUnserializeTarget()
    {
        $this->userSerializer->shouldReceive('unserialize')->with($this->serializedTarget)->andReturn($this->target);
    }

    private function itWillUnserializeTargetObject()
    {
        $this->eventTargetSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedTargetObject)
            ->andReturn($this->targetObject);
    }

    /**
     * @return \stdClass
     */
    private function getSerializedEvent()
    {
        $eventObj = new \stdClass();
        $eventObj->event = $this->type;
        $eventObj->source = $this->serializedSource;
        $eventObj->target = $this->serializedTarget;
        $eventObj->target_object = $this->serializedTargetObject;
        $eventObj->created_at = $this->date->format(TwitterDate::FORMAT);
        return $eventObj;
    }

    /**
     * @return TwitterEvent
     */
    private function getEvent()
    {
        return TwitterEvent::create($this->type, $this->source, $this->target, $this->targetObject, $this->date);
    }

    /**
     * @return TwitterSerializable
     */
    private function getIllegalObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return \stdClass
     */
    private function getIllegalSerializedObject()
    {
        return new \stdClass();
    }
}
