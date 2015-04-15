<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterPlace;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterPlaceSerializer implements TwitterSerializer
{

    /**
     * @param  \Twitter\TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterPlace)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterPlace');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return \Twitter\Object\TwitterPlace
     */
    public function unserialize($obj, array $context = array())
    {
        return new TwitterPlace();
    }
}