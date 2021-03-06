<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\TwitterCoordinates;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterCoordinatesSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var TwitterCoordinates $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterCoordinates');

        $coords = new \stdClass();
        $coords->coordinates = [$object->getLongitude(), $object->getLatitude()];
        $coords->type = $object->getType();

        return $coords;
    }

    /**
     * @param  \stdClass $obj
     * @param  array $context
     * @return TwitterCoordinates
     */
    public function unserialize($obj, array $context = [])
    {
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        $coords = $obj->coordinates;

        return TwitterCoordinates::create($coords[0], $coords[1], $obj->type);
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterCoordinates;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->coordinates);
    }

    /**
     * @return TwitterCoordinatesSerializer
     */
    public static function build()
    {
        return new self();
    }
}
