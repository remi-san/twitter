<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterFriends;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterFriendsSerializer implements TwitterSerializer
{

    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterFriends)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterFriends');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return \Twitter\Object\TwitterFriends
     */
    public function unserialize($obj, array $context = array())
    {
        return new TwitterFriends($obj->friends);
    }
} 