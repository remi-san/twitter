<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterCoordinates;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterCoordinatesSerializer implements TwitterSerializer
{

    /**
     * @param  \Twitter\TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterCoordinates)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterCoordinates');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterCoordinates
     */
    public function unserialize($obj, array $context = array())
    {
        $coords = $obj->coordinates;

        return new TwitterCoordinates($coords[0], $coords[1], $obj->type);
    }
}