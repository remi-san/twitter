<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\TwitterFriends;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterFriendsSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var TwitterFriends $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterFriends');

        $friends = new \stdClass();
        $friends->friends = $object->getFriends();

        return $friends;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterFriends
     */
    public function unserialize($obj, array $context = [])
    {
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        return TwitterFriends::create($obj->friends);
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterFriends;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->friends);
    }

    /**
     * @return TwitterFriendsSerializer
     */
    public static function build()
    {
        return new self();
    }
}
