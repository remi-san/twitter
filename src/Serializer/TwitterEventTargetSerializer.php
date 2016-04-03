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
        if (!($object instanceof TwitterEventTarget)) {
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
        $object = null;

        if (isset($obj->text) && isset($obj->user)) {
            $object = $this->tweetSerializer->unserialize($obj);
        } else {
            // List
        }

        return $object;
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
