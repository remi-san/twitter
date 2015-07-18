<?php
namespace Twitter\Serializer;

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
    function __construct(TwitterUserSerializer $userSerializer, TwitterEventTargetSerializer $targetSerializer)
    {
        $this->userSerializer = $userSerializer;
        $this->targetSerializer = $targetSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterEvent)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterEvent');
        }

        $event = new \stdClass();
        $event->event = $object->getType();
        $event->source = $this->userSerializer->serialize($object->getSource());
        $event->created_at = $object->getDate()->setTimezone(new \DateTimeZone('UTC'))->format(TwitterDate::FORMAT);

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
    public function unserialize($obj, array $context = array())
    {
        return new TwitterEvent(
            $obj->event,
            $this->userSerializer->unserialize($obj->source),
            isset($obj->target) ? $this->userSerializer->unserialize($obj->target) : null,
            isset($obj->target_object) ? $this->targetSerializer->unserialize($obj->target_object) : null,
            new \DateTime($obj->created_at)
        );
    }

} 