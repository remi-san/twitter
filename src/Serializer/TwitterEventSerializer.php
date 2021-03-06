<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\TwitterDate;
use Twitter\Object\TwitterEvent;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterEventSerializer implements TwitterSerializer
{
    /**
     * @var TwitterUserSerializer
     */
    private $userSerializer;

    /**
     * @var TwitterEventTargetSerializer
     */
    private $targetSerializer;

    /**
     * @param TwitterUserSerializer $userSerializer
     * @param TwitterEventTargetSerializer $targetSerializer
     */
    public function __construct(TwitterUserSerializer $userSerializer, TwitterEventTargetSerializer $targetSerializer)
    {
        $this->userSerializer = $userSerializer;
        $this->targetSerializer = $targetSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var TwitterEvent $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterEvent');
        Assertion::eq(new \DateTimeZone('UTC'), $object->getDate()->getTimezone());

        $event = new \stdClass();
        $event->event = $object->getType();
        $event->source = $this->userSerializer->serialize($object->getSource());
        $event->created_at = $object->getDate()->format(TwitterDate::FORMAT);

        if ($object->getTarget()) {
            $event->target = $this->userSerializer->serialize($object->getTarget());
        }

        if ($object->getObject()) {
            $event->target_object = $this->targetSerializer->serialize($object->getObject());
        }

        return $event;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterEvent
     */
    public function unserialize($obj, array $context = [])
    {
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        $createdAt = new \DateTimeImmutable($obj->created_at);
        Assertion::eq(new \DateTimeZone('UTC'), $createdAt->getTimezone());

        return TwitterEvent::create(
            $obj->event,
            $this->userSerializer->unserialize($obj->source),
            isset($obj->target) ? $this->userSerializer->unserialize($obj->target) : null,
            isset($obj->target_object) ? $this->targetSerializer->unserialize($obj->target_object) : null,
            $createdAt
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterEvent;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->event);
    }

    /**
     * @return TwitterEventSerializer
     */
    public static function build()
    {
        return new self(
            TwitterUserSerializer::build(),
            TwitterEventTargetSerializer::build()
        );
    }
}
