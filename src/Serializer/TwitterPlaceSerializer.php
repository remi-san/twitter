<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\TwitterPlace;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterPlaceSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var TwitterPlace $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterPlace');

        return new \stdClass();
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterPlace
     */
    public function unserialize($obj, array $context = [])
    {
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        return TwitterPlace::create();
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterPlace;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return $object !== null;
    }

    /**
     * @return TwitterPlaceSerializer
     */
    public static function build()
    {
        return new self();
    }
}
