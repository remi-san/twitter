<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterEntityIndicesSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterEntityIndices)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterEntityIndices');
        }

        return [$object->getFrom(), $object->getTo()];
    }

    /**
     * @param  array $array
     * @param  array $context
     * @return TwitterEntityIndices
     */
    public function unserialize($array, array $context = [])
    {
        return TwitterEntityIndices::create($array[0], $array[1]);
    }

    /**
     * @return TwitterEntityIndicesSerializer
     */
    public static function build()
    {
        return new self();
    }
}
