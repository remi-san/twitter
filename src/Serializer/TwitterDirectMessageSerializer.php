<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterDirectMessage;
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
    function __construct(TwitterUserSerializer $userSerializer, TwitterEntitiesSerializer $twitterEntitiesSerializer)
    {
        $this->userSerializer = $userSerializer;
        $this->twitterEntitiesSerializer = $twitterEntitiesSerializer;
    }

    /**
     * @param  \Twitter\TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterDirectMessage)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterDirectMessage');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $dm
     * @param  array     $context
     * @return TwitterDirectMessage
     */
    public function unserialize($dm, array $context = array())
    {
        return new TwitterDirectMessage(
            $dm->id,
            $this->userSerializer->unserialize($dm->sender),
            $this->userSerializer->unserialize($dm->recipient),
            $dm->text,
            new \DateTime($dm->created_at),
            $dm->entities?$this->twitterEntitiesSerializer->unserialize($dm->entities):null
        );
    }
} 