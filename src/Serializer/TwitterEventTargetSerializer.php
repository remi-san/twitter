<?php
namespace Twitter\Serializer;

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
    function __construct(TweetSerializer $tweetSerializer)
    {
        $this->tweetSerializer = $tweetSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterEventTarget)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterEventTarget');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterEventTarget
     */
    public function unserialize($obj, array $context = array())
    {
        $object = null;

        if (isset($obj->text) && isset($obj->user)) {
            $object = $this->tweetSerializer->unserialize($obj);
        } else {
            // List
        }

        return $object;
    }
}