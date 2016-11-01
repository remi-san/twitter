<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\TwitterEntityIndices;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterEntityIndicesSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return integer[]
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var TwitterEntityIndices $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterEntityIndices');

        return [$object->getFrom(), $object->getTo()];
    }

    /**
     * @param  array $array
     * @param  array $context
     * @return TwitterEntityIndices
     */
    public function unserialize($array, array $context = [])
    {
        Assertion::true($this->canUnserialize($array), 'object is not unserializable');

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
     * @param  array $object
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
