<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterCoordinates;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterCoordinatesSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterCoordinates)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterCoordinates');
        }

        $coords = new \stdClass();
        $coords->coordinates = array($object->getLongitude(), $object->getLatitude());
        $coords->type = $object->getType();

        return $coords;
    }

    /**
     * @param  \stdClass $obj
     * @param  array $context
     * @return TwitterCoordinates
     */
    public function unserialize($obj, array $context = array())
    {
        $coords = $obj->coordinates;

        return new TwitterCoordinates($coords[0], $coords[1], $obj->type);
    }

    /**
     * @return TwitterCoordinatesSerializer
     */
    public static function build()
    {
        return new self();
    }
}
