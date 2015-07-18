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
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterUserMention)) {
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
    public function unserialize($obj, array $context = array())
    {
        return new TwitterUserMention(
            $obj->id,
            $obj->screen_name,
            $obj->name,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
    }
}