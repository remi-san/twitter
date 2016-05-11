<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterUserMention;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterUserMentionSerializer implements TwitterSerializer
{
    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    /**
     * Constructor
     *
     * @param TwitterEntityIndicesSerializer $entityIndicesSerializer
     */
    public function __construct(TwitterEntityIndicesSerializer $entityIndicesSerializer)
    {
        $this->entityIndicesSerializer  = $entityIndicesSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterUserMention');
        }

        $userMention = new \stdClass();
        $userMention->id = $object->getId();
        $userMention->screen_name = $object->getScreenName();
        $userMention->name = $object->getName();
        $userMention->indices = $this->entityIndicesSerializer->serialize($object->getIndices());

        return $userMention;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterUserMention
     */
    public function unserialize($obj, array $context = [])
    {
        if (!$this->canUnserialize($obj)) {
            throw new \InvalidArgumentException('$object is not unserializable');
        }

        return TwitterUserMention::create(
            $obj->id,
            $obj->screen_name,
            $obj->name,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterUserMention;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->id) && isset($object->indices);
    }

    /**
     * @return TwitterUserMentionSerializer
     */
    public static function build()
    {
        return new self(
            TwitterEntityIndicesSerializer::build()
        );
    }
}
