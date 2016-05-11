<?php

namespace Twitter\Serializer;

use Twitter\Object\Tweet;
use Twitter\TwitterEventTarget;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterEventTargetSerializer implements TwitterSerializer
{
    /**
     * @var TweetSerializer
     */
    private $tweetSerializer;

    /**
     * @param TweetSerializer $tweetSerializer
     */
    public function __construct(TweetSerializer $tweetSerializer)
    {
        $this->tweetSerializer = $tweetSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterEventTarget');
        }

        if ($object instanceof Tweet) {
            return $this->tweetSerializer->serialize($object);
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterEventTarget
     */
    public function unserialize($obj, array $context = [])
    {
        if (!$this->canUnserialize($obj)) {
            return null;
        }

        return $this->tweetSerializer->unserialize($obj);
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $this->tweetSerializer->canSerialize($object) || $object instanceof TwitterEventTarget;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return $this->tweetSerializer->canUnserialize($object);
    }

    /**
     * @return TwitterEventTargetSerializer
     */
    public static function build()
    {
        return new self(
            TweetSerializer::build()
        );
    }
}
