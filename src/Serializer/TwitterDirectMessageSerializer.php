<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterDate;
use Twitter\Object\TwitterDirectMessage;
use Twitter\TwitterMessageId;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterDirectMessageSerializer implements TwitterSerializer
{
    /**
     * @var TwitterUserSerializer
     */
    private $userSerializer;

    /**
     * @var TwitterEntitiesSerializer
     */
    private $twitterEntitiesSerializer;

    /**
     * @param TwitterUserSerializer $userSerializer
     * @param TwitterEntitiesSerializer $twitterEntitiesSerializer
     */
    public function __construct(
        TwitterUserSerializer $userSerializer,
        TwitterEntitiesSerializer $twitterEntitiesSerializer
    ) {
        $this->userSerializer = $userSerializer;
        $this->twitterEntitiesSerializer = $twitterEntitiesSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterDirectMessage');
        }

        /* @var TwitterDirectMessage $object */
        $dm = new \stdClass();
        $dm->id = (string)$object->getId();
        $dm->sender = $this->userSerializer->serialize($object->getSender());
        $dm->recipient = $this->userSerializer->serialize($object->getRecipient());
        $dm->text = $object->getText();
        $dm->created_at = $object->getDate()->setTimezone(new \DateTimeZone('UTC'))->format(TwitterDate::FORMAT);
        $dm->entities = $object->getEntities()?$this->twitterEntitiesSerializer->serialize($object->getEntities()):null;

        $dmObject = new \stdClass();
        $dmObject->direct_message = $dm;

        return $dmObject;
    }

    /**
     * @param  \stdClass $directMessage
     * @param  array     $context
     * @return TwitterDirectMessage
     */
    public function unserialize($directMessage, array $context = [])
    {
        if (!$this->canUnserialize($directMessage)) {
            throw new \InvalidArgumentException('$object is not unserializable');
        }

        $dm = $directMessage->direct_message;
        return TwitterDirectMessage::create(
            TwitterMessageId::create($dm->id),
            $this->userSerializer->unserialize($dm->sender),
            $this->userSerializer->unserialize($dm->recipient),
            $dm->text,
            new \DateTimeImmutable($dm->created_at),
            $this->twitterEntitiesSerializer->canUnserialize($dm->entities) ?
            $this->twitterEntitiesSerializer->unserialize($dm->entities) : null
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterDirectMessage;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return (isset($object->direct_message));
    }

    /**
     * @return TwitterDirectMessageSerializer
     */
    public static function build()
    {
        return new self(
            TwitterUserSerializer::build(),
            TwitterEntitiesSerializer::build()
        );
    }
}
