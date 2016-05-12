<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterEntityIndicesSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterEntityIndices');
        }

        /* @var TwitterEntityIndices $object */
        return [$object->getFrom(), $object->getTo()];
    }

    /**
     * @param  array $array
     * @param  array $context
     * @return TwitterEntityIndices
     */
    public function unserialize($array, array $context = [])
    {
        if (!$this->canUnserialize($array)) {
            throw new \InvalidArgumentException('$object is not unserializable');
        }

        return TwitterEntityIndices::create($array[0], $array[1]);
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterEntityIndices;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return is_array($object) && count($object) === 2;
    }

    /**
     * @return TwitterEntityIndicesSerializer
     */
    public static function build()
    {
        return new self();
    }
}
