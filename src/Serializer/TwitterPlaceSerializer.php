<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterPlace;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterPlaceSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterPlace)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterPlace');
        }

        return new \stdClass();
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterPlace
     */
    public function unserialize($obj, array $context = array())
    {
        return TwitterPlace::create();
    }

    /**
     * @return TwitterPlaceSerializer
     */
    public static function build()
    {
        return new self();
    }
}
