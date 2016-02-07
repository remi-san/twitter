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

        $friends = new \stdClass();
        $friends->friends = $object->getFriends();

        return $friends;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterFriends
     */
    public function unserialize($obj, array $context = array())
    {
        return TwitterFriends::create($obj->friends);
    }

    /**
     * @return TwitterFriendsSerializer
     */
    public static function build()
    {
        return new self();
    }
}
