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
        if (!($object instanceof TwitterDirectMessage)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterDirectMessage');
        }

        $dm = new \stdClass();
        $dm->id = (string)$object->getId();
        $dm->sender = $this->userSerializer->serialize($object->getSender());
        $dm->recipient = $this->userSerializer->serialize($object->getRecipient());
        $dm->text = $object->getText();
        $dm->created_at = $object->getDate()->setTimezone(new \DateTimeZone('UTC'))->format(TwitterDate::FORMAT);
        $dm->entities = $object->getEntities()?$this->twitterEntitiesSerializer->serialize($object->getEntities()):null;

        return $dm;
    }

    /**
     * @param  \stdClass $dm
     * @param  array     $context
     * @return TwitterDirectMessage
     */
    public function unserialize($dm, array $context = [])
    {
        return TwitterDirectMessage::create(
            TwitterMessageId::create($dm->id),
            $this->userSerializer->unserialize($dm->sender),
            $this->userSerializer->unserialize($dm->recipient),
            $dm->text,
            new \DateTimeImmutable($dm->created_at),
            $dm->entities?$this->twitterEntitiesSerializer->unserialize($dm->entities):null
        );
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
